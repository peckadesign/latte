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
		public ?Node $value = null,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = 'echo ' . var_export($this->name, true) . ';';
		if ($this->value) {
			$res .= "echo '=';";
			$res .= $this->value->print($context);
		}
		return $res;
	}


	public function &getIterator(): \Generator
	{
		if ($this->value) {
			yield $this->value;
		}
	}
}
