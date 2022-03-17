<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class VariableNode extends ExprNode
{
	public function __construct(
		public string|ExprNode $name,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $this->name instanceof ExprNode
			? '${' . $this->name->print($context) . '}'
			: '$' . $this->name;
	}


	public function &getIterator(): \Generator
	{
		if ($this->name instanceof ExprNode) {
			yield $this->name;
		}
	}
}
