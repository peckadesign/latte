<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\Expr\CallLikeNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;


class UndefinedsafeMethodCallNode extends CallLikeNode
{
	public ExprNode $var;
	public IdentifierNode|ExprNode $name;


	public function __construct(Php\Expr\UndefinedsafeMethodCallNode $source)
	{
		$this->var = $source->var;
		$this->name = $source->name;
		$this->args = $source->args;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->callMethod(' . $context->dereferenceExpr($this->var) . ' ?? null, ' . $context->propertyAsValue($this->name) . ')'
			. '?->__invoke'
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
