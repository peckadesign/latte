<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\PrintContext;


class TernaryNode extends ExprNode
{
	public function __construct(
		public ExprNode $cond,
		public ?ExprNode $if,
		public ?ExprNode $else,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $context->infixOp(
			$this,
			$this->cond,
			' ?' . ($this->if !== null ? ' ' . $this->if->print($context) . ' ' : '') . ': ',
			$this->else ?? new NameNode('null'),
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->cond;
		if ($this->if) {
			yield $this->if;
		}
		if ($this->else) {
			yield $this->else;
		}
	}
}
