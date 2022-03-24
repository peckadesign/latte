<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox\Nodes;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\Nodes\Php\VarLikeIdentifierNode;
use Latte\Compiler\PrintContext;


class StaticPropertyFetchNode extends ExprNode
{
	public NameNode|ExprNode $class;
	public VarLikeIdentifierNode|ExprNode $name;


	public function __construct(Php\Expr\StaticPropertyFetchNode $source)
	{
		$this->class = $source->class;
		$this->name = $source->name;
	}


	public function print(PrintContext $context): string
	{
		return '$this->global->sandbox->prop(' . $context->propertyAsValue($this->class) . ', ' . $context->propertyAsValue($this->name) . ')'
			. '::$'
			. $context->objectProperty($this->name);
	}


	public function &getIterator(): \Generator
	{
		yield $this->class;
		yield $this->name;
	}
}
