<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\PrintContext;


class AttributeNode extends ContentNode
{
	public function __construct(
		public string $name,
		public string $text,
		public ?Node $value = null,
		public ?string $quote = null,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = 'echo ' . var_export($this->text, true) . ';';
		if ($this->value) {
			$res .= $this->value->print($context);
		}
		return $res;
	}
}
