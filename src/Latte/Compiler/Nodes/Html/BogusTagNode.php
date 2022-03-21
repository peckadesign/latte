<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\PrintContext;
use Latte\Context;


/**
 * HTML bogus tag.
 */
class BogusTagNode extends AreaNode
{
	public function __construct(
		public string $openDelimiter,
		public Node $content,
		public string $endDelimiter,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = 'echo ' . var_export($this->openDelimiter, true) . ';';
		$context->setEscapingContext(Context::HtmlBogusTag);
		$res .= $this->content->print($context);
		$context->setEscapingContext(Context::HtmlText);
		$res .= 'echo ' . var_export($this->endDelimiter, true) . ';';
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
