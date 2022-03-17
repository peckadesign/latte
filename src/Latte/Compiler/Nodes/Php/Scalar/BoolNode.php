<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PrintContext;


class BoolNode extends ScalarNode
{
	public function __construct(
		public bool $value,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return $this->value ? 'true' : 'false';
	}
}
