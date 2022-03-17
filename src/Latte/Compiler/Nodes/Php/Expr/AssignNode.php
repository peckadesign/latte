<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class AssignNode extends ExprNode
{
	public function __construct(
		public ExprNode $var,
		public ExprNode $expr,
		public bool $byRef = false,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->infixOp($this, $this->var, $this->byRef ? ' = &' : ' = ', $this->expr);
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		yield $this->expr;
	}
}
