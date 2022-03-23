<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Context;
use Latte\Runtime\Template;


/**
 * {block [local] [name]}
 */
class BlockNode extends StatementNode
{
	public ?Block $block = null;
	public string $filter;
	public ContentNode $content;
	public bool $extendsCheck;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self|FragmentNode> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->extractModifier();
		[$name, $local] = $tag->tokenizer->fetchWordWithModifier('local');
		if ($token = $tag->tokenizer->nextValue()) {
			throw new CompileException("Unexpected arguments '$token' in " . $tag->getNotation(), $tag->line);
		}
		$name = ltrim((string) $name, '#');
		$node = new self;

		if ($name !== '') {
			$layer = $local ? Template::LayerLocal : $parser->blockLayer;
			$node->block = new Block($name, $layer, $tag);

			if (!$node->block->isDynamic()) {
				$node->extendsCheck = $parser->blocks[Template::LayerTop] || count($parser->blocks) > 1 || $tag->parent;
				$parser->checkBlockIsUnique($node->block);
				$tag->data->block = $node->block; // for {include}
			}
		}

		$node->filter = $tag->modifiers;
		[$node->content] = yield;

		if ($name === '') {
			if ($node->filter === '') {
				return $node->content;
			}

			$node->filter .= '|escape';
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		if (!$this->block) {
			return $this->printFilter($context);

		} elseif ($this->block->isDynamic()) {
			return $this->printDynamic($context);
		}

		return $this->printStatic($context);
	}


	private function printFilter(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent(ob_get_clean());
				}

				XX,
			$this->filter,
			$this->line,
			$this->content,
			implode('', $context->getEscapingContext()),
		);
	}


	private function printStatic(PrintContext $context): string
	{
		$context->addBlock($this->block, $this->adjustContext($context->getEscapingContext()));
		$this->block->content = $this->content->print($context); // must be compiled after is added

		return $context->format(
			($this->extendsCheck ? '' : 'if ($this->getParentName()) { return get_defined_vars(); } ')
			. '$this->renderBlock(%dump, get_defined_vars()'
			. ($this->filter
				? $context->format(
					', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->filter,
				)
				: '')
			. ') %line;',
			$this->block->name,
			$this->line,
		);
	}


	private function printDynamic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added
		$escapingContext = $this->adjustContext($context->getEscapingContext());

		return $context->format(
			'$this->addBlock($ʟ_nm = %word, %dump, [[$this, %dump]], %dump);
			$this->renderBlock($ʟ_nm, get_defined_vars()'
			. ($this->filter
				? $context->format(
					', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }',
					$this->filter,
				)
				: '')
			. ');',
			$this->block->name,
			implode('', $escapingContext),
			$this->block->method,
			$this->block->layer,
		);
	}


	private function adjustContext(array $context): array
	{
		if (str_starts_with((string) $context[1], Context::HtmlAttribute)) {
			$context[1] = null;
			$this->filter .= '|escape';
		} elseif ($this->filter) {
			$this->filter .= '|escape';
		}
		return $context;
	}


	public function getOutputMode(): int
	{
		return self::OutputBlock;
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
