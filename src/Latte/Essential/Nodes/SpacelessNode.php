<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Context;


/**
 * {spaceless}
 */
class SpacelessNode extends StatementNode
{
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		$node = new self;
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				ob_start('Latte\Essential\Filters::%raw', 4096) %line;
				try {
					%raw
				} finally {
					ob_end_flush();
				}


				XX,
			$context->getEscapingContext()[0] === Context::Html
				? 'spacelessHtmlHandler'
				: 'spacelessText',
			$this->line,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
