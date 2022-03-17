<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;


class UndefinedsafeMethodCallNode extends CallLikeNode
{
	public IdentifierNode|ExprNode $name;


	public function __construct(
		public ExprNode $var,
		string|IdentifierNode|ExprNode $name,
		/** @var array<Php\ArgNode|Php\VariadicPlaceholderNode> */
		public array $args = [],
		public ?int $line = null,
	) {
		$this->name = is_string($name) ? new IdentifierNode($name) : $name;
	}


	public function print(PrintContext $context): string
	{
		return $context->dereferenceExpr(new BinaryOpNode($this->var, '??', new Php\Scalar\NullNode))
			. '?->'
			. $context->objectProperty($this->name)
			. '(' . $context->implode($this->args) . ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
