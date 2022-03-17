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


class ConstFetchNode extends ExprNode
{
	public function __construct(
		public NameNode $name,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $this->name->print($context);
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
	}
}
