<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;

use Latte\Compiler\PrintContext;


class TextNode extends ContentNode
{
	public function __construct(
		public string $content,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return 'echo ' . var_export($this->content, true) . ";\n";
	}
}
