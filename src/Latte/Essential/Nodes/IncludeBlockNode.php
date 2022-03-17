<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Helpers;
use Latte\Runtime\Template;


/**
 * {include [block] name [from file] [, args]}
 */
class IncludeBlockNode extends StatementNode
{
	public string $name;
	public ?LegacyExprNode $from = null;
	public ?LegacyExprNode $args = null;
	public string $filter;
	public int|string|null $layer;
	public bool $parent = false;

	/** @var Block[][] */
	public array $blocks;


	public static function create(Tag $tag, TemplateParser $parser): self
	{
		$node = new self;
		[$node->name] = $tag->tokenizer->fetchWordWithModifier(['block', '#']);

		if ($tag->tokenizer->nextValue('from')) {
			$tag->tokenizer->nextValue($tag->tokenizer::T_WHITESPACE);
			$node->from = $tag->getWord();
		}

		$node->args = $tag->getArgs();
		$node->filter = $tag->modifiers;

		$node->parent = $node->name === 'parent';
		if ($node->parent && $tag->modifiers !== '') {
			throw new CompileException('Filters are not allowed in {include parent}', $tag->line);

		} elseif ($node->parent || $node->name === 'this') {
			$item = $tag->closest(['block', 'define'], fn($item) => isset($item->data->block) && $item->data->block->name !== '');
			if (!$item) {
				throw new CompileException("Cannot include $node->name block outside of any block.", $tag->line);
			}

			$node->name = $item->data->block->name;
		}

		$node->blocks = &$parser->blocks;
		$node->layer = $parser->blockLayer;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$filter = $this->filter;
		$noEscape = Helpers::removeFilter($filter, 'noescape');
		if ($filter && !$noEscape) {
			$filter .= '|escape';
		}
		$filterArg = $filter
			? $context->format(
				'function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
				$filter,
			)
			: PhpHelpers::dump($noEscape || $this->parent ? null : implode('', $context->getEscapingContext()));

		return $this->from
			? $this->printBlockFrom($context, $filterArg)
			: $this->printBlock($context, $filterArg);
	}


	private function printBlock(PrintContext $context, string $filterArg): string
	{
		$block = $this->blocks[$this->layer][$this->name] ?? $this->blocks[Template::LayerLocal][$this->name] ?? null;
		return $context->format(
			'$this->renderBlock' . ($this->parent ? 'Parent' : '')
			. '(' . (Helpers::isNameDynamic($this->name) ? '%word' : '%dump') . ', '
			. '%array? + '
			. ($block && !$block->parameters ? 'get_defined_vars()' : '[]')
			. '%raw) %line;',
			$this->name,
			$this->args,
			$filterArg === 'null' ? '' : ", $filterArg",
			$this->line,
		);
	}


	private function printBlockFrom(PrintContext $context, string $filterArg): string
	{
		return $context->format(
			'$this->createTemplate(%word, %array? + $this->params, "include")->renderToContentType(%raw, %word) %line;',
			$this->from,
			$this->args,
			$filterArg,
			$this->name,
			$this->line,
		);
	}


	public function getOutputMode(): int
	{
		return self::OutputBlock;
	}


	public function &getIterator(): \Generator
	{
		if ($this->from) {
			yield $this->from;
		}
		yield $this->args;
	}
}
