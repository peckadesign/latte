<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class ArrayAccessNode extends ExprNode
{
	public function __construct(
		public ExprNode $var,
		public ?ExprNode $index = null,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->dereferenceExpr($this->var)
			. '[' . ($this->index !== null ? $this->index->print($context) : '') . ']';
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		if ($this->index) {
			yield $this->index;
		}
	}
}
