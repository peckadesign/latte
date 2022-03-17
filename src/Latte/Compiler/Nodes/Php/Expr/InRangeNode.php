<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class InRangeNode extends ExprNode
{
	public function __construct(
		public ExprNode $needle,
		public ExprNode $haystack,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return 'in_array('
			. $this->needle->print($context)
			. ', '
			. $this->haystack->print($context)
			. ', true)';
	}


	public function &getIterator(): \Generator
	{
		yield $this->needle;
		yield $this->haystack;
	}
}
