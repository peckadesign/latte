<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class BinaryOpNode extends ExprNode
{
	private const Ops = ['||', '&&', 'or', 'and', 'xor', '&', '^', '.', '+', '-', '*', '/', '%', '<<', '>>', '**',
		'===', '!==', '==', '!==', '<=>', '<', '<=', '>', '>=', '??', ];


	public function __construct(
		public ExprNode $left,
		public /*readonly*/ string $operator,
		public ExprNode $right,
		public ?int $line = null,
	) {
		if (!in_array(strtolower($this->operator), self::Ops, true)) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
	}


	/**
	 * Creates nested BinaryOp nodes from a list of expressions.
	 */
	public static function nest(string $operator, ExprNode ...$exprs): ExprNode
	{
		$count = count($exprs);
		if ($count < 2) {
			return $exprs[0];
		}

		$last = $exprs[0];
		for ($i = 1; $i < $count; $i++) {
			$last = new self($last, $operator, $exprs[$i]);
		}

		return $last;
	}


	public function print(PrintContext $context): string
	{
		return $context->infixOp($this, $this->left, ' ' . $this->operator . ' ', $this->right);
	}


	public function &getIterator(): \Generator
	{
		yield $this->left;
		yield $this->right;
	}
}
