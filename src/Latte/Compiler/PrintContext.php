<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php as Nodes;
use Latte\Compiler\Nodes\Php\Expr;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Context;
use Latte\Strict;


/**
 * PHP printing helpers and context.
 * The parts are based on great nikic/PHP-Parser project by Nikita Popov.
 */
final class PrintContext
{
	use Strict;

	public array $paramsExtraction = [];
	public array $blocks = [];

	private $exprPrecedenceMap = [
		// [precedence, associativity] (-1 is %left, 0 is %nonassoc and 1 is %right)
		Expr\PreOpNode::class              => [10,  1],
		Expr\PostOpNode::class             => [10, -1],
		Expr\UnaryOpNode::class            => [10,  1],
		Expr\CastNode::class               => [10,  1],
		Expr\ErrorSuppressNode::class      => [10,  1],
		Expr\InstanceofNode::class         => [20,  0],
		Expr\NotNode::class                => [30,  1],
		Expr\TernaryNode::class            => [150,  0],
		// parser uses %left for assignments, but they really behave as %right
		Expr\AssignNode::class             => [160,  1],
		Expr\AssignOpNode::class           => [160,  1],
	];

	private $binaryPrecedenceMap = [
		// [precedence, associativity] (-1 is %left, 0 is %nonassoc and 1 is %right)
		'**'  => [0, 1],
		'*'   => [40, -1],
		'/'   => [40, -1],
		'%'   => [40, -1],
		'+'   => [50, -1],
		'-'   => [50, -1],
		'.'   => [50, -1],
		'<<'  => [60, -1],
		'>>'  => [60, -1],
		'<'   => [70, 0],
		'<='  => [70, 0],
		'>'   => [70, 0],
		'>='  => [70, 0],
		'=='  => [80, 0],
		'!='  => [80, 0],
		'===' => [80, 0],
		'!==' => [80, 0],
		'<=>' => [80, 0],
		'&'   => [90, -1],
		'^'   => [100, -1],
		'|'   => [110, -1],
		'&&'  => [120, -1],
		'||'  => [130, -1],
		'??'  => [140, 1],
		'and' => [170, -1],
		'xor' => [180, -1],
		'or'  => [190, -1],
	];
	private int $counter = 0;
	private string $contentType = Context::Html;
	private ?string $context = null;
	private ?string $subContext = null;


	/**
	 * Expands %line, %dump, %raw, %modify() in code.
	 */
	public function format(string $mask, mixed ...$args): string
	{
		if (str_contains($mask, '%modify')) {
			$modifier = array_shift($args);
			$mask = preg_replace_callback(
				'#%modify(Content)?(\(([^()]*+|(?2))+\))#',
				function ($m) use ($modifier) {
					$var = substr($m[2], 1, -1);
					if (!$modifier) {
						return $var;
					}
					return $m[1]
						? $modifier->printContent($this, $var)
						: $modifier->print($this, $var);
				},
				$mask,
			);
		}

		return preg_replace_callback(
			'#([,+]?\s*)?%(\d+\.|)(dump|raw|line)(\?)?(\s*\+\s*)?()#',
			function ($m) use (&$args) {
				[, $l, $source, $format, $cond, $r] = $m;

				switch ($source) {
					case '':
						$arg = current($args);
						next($args);
						break;
					default:
						$arg = $args[(int) $source];
				}

				switch ($format) {
					case 'dump':
						$code = PhpHelpers::dump($arg);
						break;
					case 'raw':
						$code = $arg instanceof Node ? $arg->print($this) : $arg;
						if ($cond && ($code === '[]' || $code === '')) {
							return $r ? $l : $r;
						}
						break;
					case 'line':
						$l = trim($l);
						$line = (int) $arg;
						$code = $line ? " /* line $line */" : '';
						break;
				}

				return $l . $code . $r;
			},
			$mask,
		);
	}


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->context = null;
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	public function setEscapingContext(?string $context, ?string $subContext = null): static
	{
		$this->context = $context;
		$this->subContext = $subContext;
		return $this;
	}


	public function getEscapingContext(): array
	{
		return [$this->contentType, $this->context, $this->subContext];
	}


	public function addBlock(Block $block, ?array $context = null): void
	{
		$block->context = implode('', $context ?? $this->getEscapingContext());
		$block->method = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $block->name->print($this)), '_'));
		$lower = strtolower($block->method);
		$used = $this->blocks + ['block' => 1];
		$counter = null;
		while (isset($used[$lower . $counter])) {
			$counter++;
		}

		$block->method .= $counter;
		$this->blocks[$lower . $counter] = $block;
	}


	// PHP helpers


	public function encodeString(string $str, string $quote = "'"): string
	{
		return $quote === "'"
			? "'" . addcslashes($str, "'\\") . "'"
			: '"' . addcslashes($str, "\n\r\t\f\v$\"\\") . '"';
	}


	/**
	 * Prints an infix operation while taking precedence into account.
	 */
	public function infixOp(Node $node, Node $leftNode, string $operatorString, Node $rightNode): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $this->prec($leftNode, $precedence, $associativity, -1)
			. $operatorString
			. $this->prec($rightNode, $precedence, $associativity, 1);
	}


	/**
	 * Prints a prefix operation while taking precedence into account.
	 */
	public function prefixOp(Node $node, string $operatorString, Node $expr): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $operatorString . $this->prec($expr, $precedence, $associativity, 1);
	}


	/**
	 * Prints a postfix operation while taking precedence into account.
	 */
	public function postfixOp(Node $node, Node $var, string $operatorString): string
	{
		[$precedence, $associativity] = $this->getPrecedence($node);
		return $this->prec($var, $precedence, $associativity, -1) . $operatorString;
	}


	/**
	 * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
	 */
	private function prec(Node $node, int $parentPrecedence, int $parentAssociativity, int $childPosition): string
	{
		$precedence = $this->getPrecedence($node);
		if ($precedence) {
			$childPrecedence = $precedence[0];
			if ($childPrecedence > $parentPrecedence
				|| ($parentPrecedence === $childPrecedence && $parentAssociativity !== $childPosition)
			) {
				return '(' . $node->print($this) . ')';
			}
		}

		return $node->print($this);
	}


	private function getPrecedence(Node $node): ?array
	{
		return $node instanceof Expr\BinaryOpNode
			? $this->binaryPrecedenceMap[$node->operator]
			: $this->exprPrecedenceMap[$node::class] ?? null;
	}


	/**
	 * Prints an array of nodes and implodes the printed values with $glue
	 */
	public function implode(array $nodes, string $glue = ', '): string
	{
		$pNodes = [];
		foreach ($nodes as $node) {
			if ($node === null) {
				$pNodes[] = '';
			} else {
				$pNodes[] = $node->print($this);
			}
		}

		return implode($glue, $pNodes);
	}


	public function objectProperty($node): string
	{
		if ($node instanceof ExprNode) {
			return '{' . $node->print($this) . '}';
		} else {
			return (string) $node;
		}
	}


	/**
	 * Wraps the LHS of a call in parentheses if needed.
	 */
	public function callExpr(Node $expr): string
	{
		return $expr instanceof Nodes\NameNode
			|| $expr instanceof Expr\VariableNode
			|| $expr instanceof Expr\ArrayAccessNode
			|| $expr instanceof Expr\FuncCallNode
			|| $expr instanceof Expr\MethodCallNode
			|| $expr instanceof Expr\NullsafeMethodCallNode
			|| $expr instanceof Expr\StaticCallNode
			|| $expr instanceof Expr\ArrayNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}


	/**
	 * Wraps the LHS of a dereferencing operation in parentheses if needed.
	 */
	public function dereferenceExpr(Node $expr): string
	{
		return $expr instanceof Expr\VariableNode
			|| $expr instanceof Nodes\NameNode
			|| $expr instanceof Expr\ArrayAccessNode
			|| $expr instanceof Expr\PropertyFetchNode
			|| $expr instanceof Expr\NullsafePropertyFetchNode
			|| $expr instanceof Expr\StaticPropertyFetchNode
			|| $expr instanceof Expr\FuncCallNode
			|| $expr instanceof Expr\MethodCallNode
			|| $expr instanceof Expr\NullsafeMethodCallNode
			|| $expr instanceof Expr\StaticCallNode
			|| $expr instanceof Expr\ArrayNode
			|| $expr instanceof Scalar\StringNode
			|| $expr instanceof Scalar\BoolNode
			|| $expr instanceof Scalar\NullNode
			|| $expr instanceof Expr\ConstFetchNode
			|| $expr instanceof Expr\ClassConstFetchNode
			? $expr->print($this)
			: '(' . $expr->print($this) . ')';
	}
}
