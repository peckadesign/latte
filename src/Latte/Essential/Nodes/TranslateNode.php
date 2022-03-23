<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\Php\Expr\FilterNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * {_ ...}
 */
class TranslateNode extends StatementNode
{
	public ?ContentNode $content = null;
	public ?ExprNode $expression = null;
	public ?FilterNode $filter;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self|NopNode> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$node = new self;
		$stream = $tag->parser->stream;
		if ($stream->current() && !$stream->is('|')) {
			$node->expression = $tag->parser->parseExpression();
		}

		$node->filter = $tag->parser->parseFilters();
		if (!$node->expression) {
			if ($tag->void) {
				return new NopNode;
			}
			[$node->content] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		$filter = (string) $this->filter?->name === 'noescape'
			? $this->filter->inner
			: FilterNode::escapeFilter($this->filter);

		return $this->content
			? $this->printCapturing($context, $filter)
			: $this->printExpression($context, $filter);
	}


	private function printExpression(PrintContext $context, ?FilterNode $filter): string
	{
		return $context->format(
			'echo %modify(($this->filters->translate)(%raw)) %line;',
			$filter,
			$this->expression,
			$this->line,
		);
	}


	private function printCapturing(PrintContext $context, ?FilterNode $filter): string
	{
		if (
			$this->content instanceof FragmentNode
			&& count($this->content->children) === 1
			&& $this->content->children[0] instanceof TextNode
		) {
			return $context->format(
				<<<'XX'
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent($this->filters->filterContent('translate', $ʟ_fi, %dump)) %line;
					XX,
				$filter,
				implode('', $context->getEscapingContext()),
				$this->content->children[0]->content,
				$this->line,
			);

		} else {
			return $context->format(
				<<<'XX'
					ob_start(fn() => ''); try {
						%raw
					} finally {
						$ʟ_tmp = ob_get_clean();
					}
					$ʟ_fi = new LR\FilterInfo(%dump);
					echo %modifyContent($this->filters->filterContent('translate', $ʟ_fi, $ʟ_tmp)) %line;
					XX,
				$filter,
				$this->content,
				implode('', $context->getEscapingContext()),
				$this->line,
			);
		}
	}


	public function getOutputMode(): int
	{
		return self::OutputInline;
	}


	public function &getIterator(): \Generator
	{
		if ($this->content) {
			yield $this->content;
		}
		if ($this->expression) {
			yield $this->expression;
		}
		if ($this->filter) {
			yield $this->filter;
		}
	}
}
