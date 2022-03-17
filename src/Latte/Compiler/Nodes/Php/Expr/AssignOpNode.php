<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class AssignOpNode extends ExprNode
{
	private const Ops = ['+', '-', '*', '/', '.', '%', '&', '|', '^', '<<', '>>', '**', '??'];


	public function __construct(
		public ExprNode $var,
		public /*readonly*/ string $operator,
		public ExprNode $expr,
		public ?int $line = null,
	) {
		if (!in_array($this->operator, self::Ops, true)) {
			throw new \InvalidArgumentException("Unexpected operator '$this->operator'");
		}
	}


	public function print(PrintContext $context): string
	{
		return $context->infixOp($this, $this->var, ' ' . $this->operator . '= ', $this->expr);
	}


	public function &getIterator(): \Generator
	{
		yield $this->var;
		yield $this->expr;
	}
}
