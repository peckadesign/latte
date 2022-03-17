<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class NewNode extends CallLikeNode
{
	public function __construct(
		public Php\NameNode|ExprNode $class,
		/** @var Php\ArgNode[] */
		public array $args = [],
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return 'new ' . $context->dereferenceExpr($this->class)
			. ($this->args ? '(' . $context->implode($this->args) . ')' : '');
	}


	public function &getIterator(): \Generator
	{
		yield $this->class;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
