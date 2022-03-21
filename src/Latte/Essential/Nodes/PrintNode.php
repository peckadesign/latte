<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Context;
use Latte\Helpers;


/**
 * {= ...}
 */
class PrintNode extends StatementNode
{
	public LegacyExprNode $expression;
	public string $filter;


	public static function create(Tag $tag, TemplateParser $parser): self
	{
		$stream = $parser->getStream();
		if (
			$tag->isInText()
			&& $parser->getContentType() === Context::Html
			&& $tag->htmlElement?->name === 'script'
			&& ($token = $stream->peek(0))
			&& preg_match('#["\']#A', $token->text)
		) {
			throw new CompileException("Do not place {$tag->getNotation(true)} inside quotes in JavaScript.", $tag->line);
		}

		$tag->extractModifier();
		$tag->expectArguments();
		$node = new self;
		$node->expression = $tag->getArgs();
		$node->filter = $tag->modifiers;
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

		return $context->format(
			"echo %modify(%raw) %line;\n",
			$filter,
			$this->expression,
			$this->line,
		);
	}


	public function getOutputMode(): int
	{
		return self::OutputInline;
	}


	public function &getIterator(): \Generator
	{
		yield $this->expression;
	}
}
