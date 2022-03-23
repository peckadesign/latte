<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Block;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Runtime\SnippetDriver;
use Latte\Runtime\Template;


/**
 * {snippetArea [name]}
 */
class SnippetAreaNode extends StatementNode
{
	public Block $block;
	public ContentNode $content;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$node = new self;
		$name = (string) $tag->tokenizer->fetchWord();
		$node->block = new Block($name, Template::LayerSnippet, $tag);
		$parser->checkBlockIsUnique($node->block);
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $context->format(
			<<<'XX'
				$this->global->snippetDriver->enter(%dump, %dump);
				try {
					%raw
				} finally {
					$this->global->snippetDriver->leave();
				}

				XX,
			$this->block->name,
			SnippetDriver::TYPE_AREA,
			$this->content,
		);

		return $context->format(
			'$this->renderBlock(%dump, [], null, %dump) %line;',
			$this->block->name,
			Template::LayerSnippet,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
