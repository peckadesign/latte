<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\Node;
use Latte\Compiler\PrintContext;


class ArgNode extends Node
{
	public function __construct(
		public ExprNode $value,
		public bool $byRef = false,
		public bool $unpack = false,
		public ?int $line = null,
		public ?IdentifierNode $name = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return ($this->name ? $this->name . ': ' : '')
			. ($this->byRef ? '&' : '')
			. ($this->unpack ? '...' : '')
			. $this->value->print($context);
	}


	public function &getIterator(): \Generator
	{
		if ($this->name) {
			yield $this->name;
		}
		yield $this->value;
	}
}
