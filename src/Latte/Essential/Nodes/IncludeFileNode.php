<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Helpers;


/**
 * {include [file] "file" [with blocks] [,] [params]}
 */
class IncludeFileNode extends StatementNode
{
	public string $file;
	public LegacyExprNode $args;
	public string $filter;
	public string $mode;


	public static function create(Tag $tag): self
	{
		$node = new self;
		[$node->file] = $tag->tokenizer->fetchWordWithModifier('file');
		$node->mode = 'include';
		if ($tag->tokenizer->isNext('with') && !$tag->tokenizer->isPrev(',')) {
			$tag->tokenizer->consumeValue('with');
			$tag->tokenizer->consumeValue('blocks');
			$node->mode = 'includeblock';
		}

		$node->args = $tag->getArgs();
		$node->filter = $tag->modifiers;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$filter = $this->filter;
		$noEscape = Helpers::removeFilter($filter, 'noescape');
		if ($filter && !$noEscape) {
			$filter .= '|escape';
		}

		return $context->format(
			'$this->createTemplate(%word, %array? + $this->params, %dump)->renderToContentType(%raw) %line;',
			$this->file,
			$this->args,
			$this->mode,
			$filter
				? $context->format(
					'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$filter,
				)
				: PhpHelpers::dump($noEscape ? null : implode('', $context->getEscapingContext())),
			$this->line,
		);
	}


	public function getOutputMode(): int
	{
		return self::OutputBlock;
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
	}
}
