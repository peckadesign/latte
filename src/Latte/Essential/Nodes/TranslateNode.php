<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\NopNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Helpers;


/**
 * {_ ...}
 */
class TranslateNode extends StatementNode
{
	public ?ContentNode $content = null;
	public ?LegacyExprNode $expression = null;
	public ?string $filter;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self|NopNode> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->extractModifier();
		$node = new self;
		$node->expression = $tag->getArgs();
		$node->filter = $tag->modifiers;

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
		$filter = $this->filter;
		if (Helpers::removeFilter($filter, 'noescape')) {
			$context->checkFilterIsAllowed('noescape');
		} else {
			$filter .= '|escape';
		}

		return $this->content
			? $this->printCapturing($context, $filter)
			: $this->printExpression($context, $filter);
	}


	private function printExpression(PrintContext $context, ?string $filter): string
	{
		return $context->format(
			'echo %modify(($this->filters->translate)(%args)) %line;',
			$filter,
			$this->expression,
			$this->line,
		);
	}


	private function printCapturing(PrintContext $context, ?string $filter): string
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
	}
}
