<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;


class AuxiliaryNode extends AreaNode
{
	public function __construct(
		public \Closure $callable,
		public ?string $label = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return ($this->callable)($context);
	}
}
