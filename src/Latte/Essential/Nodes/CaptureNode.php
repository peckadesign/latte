<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Php\Expr;
use Latte\Compiler\Nodes\Php\Expr\FilterNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Context;


/**
 * {capture $variable}
 */
class CaptureNode extends StatementNode
{
	public ExprNode $variable;
	public ?FilterNode $filter = null;
	public ContentNode $content;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$variable = $tag->parser->parseExpression();
		if (!self::canBeAssignedTo($variable)) {
			$source = trim($tag->parser->stream->getText(0, $tag->parser->stream->getIndex()));
			throw new CompileException("It is not possible to write into '$source' in " . $tag->getNotation(), $tag->line);
		}
		$node = new self;
		$node->variable = $variable;
		$node->filter = $tag->parser->parseFilters();
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$escapingContext = implode('', $context->getEscapingContext());
		$body = $escapingContext === Context::Html
			? 'ob_get_length() ? new LR\\Html(ob_get_clean()) : ob_get_clean()'
			: 'ob_get_clean()';

		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_tmp = %raw;
				}
				$ʟ_fi = new LR\FilterInfo(%dump); %raw = %modifyContent($ʟ_tmp);


				XX,
			$this->filter,
			$this->line,
			$this->content,
			$body,
			$escapingContext,
			$this->variable,
		);
	}


	private static function canBeAssignedTo(Node $node): bool
	{
		return $node instanceof Expr\VariableNode
			|| $node instanceof Expr\ArrayAccessNode
			|| $node instanceof Expr\PropertyFetchNode
			|| $node instanceof Expr\StaticPropertyFetchNode;
	}


	public function &getIterator(): \Generator
	{
		yield $this->variable;
		if ($this->filter) {
			yield $this->filter;
		}
		yield $this->content;
	}
}
