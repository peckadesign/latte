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
use Latte\Compiler\PrintContext;


class FuncCallNode extends CallLikeNode
{
	public Php\NameNode|ExprNode $name;


	public function __construct(Php\Expr\FuncCallNode $source)
	{
		$this->name = $source->name;
		$this->args = $source->args;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->call(' . $context->propertyAsValue($this->name) . ')'
			. '(' . $context->implode($this->args) . ')';
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
