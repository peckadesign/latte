<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {for $init; $cond; $next}
 */
class ForNode extends StatementNode
{
	public ?ExprNode $init = null;
	public ?ExprNode $condition = null;
	public ?ExprNode $next = null;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$stream = $tag->parser->stream;
		$node = new self;
		$node->init = $stream->is(';') ? null : $tag->parser->parseExpression();
		$stream->consume(';');
		$node->condition = $stream->is(';') ? null : $tag->parser->parseExpression();
		$stream->consume(';');
		$node->next = $tag->parser->isEnd() ? null : $tag->parser->parseExpression();
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				for (%raw; %raw; %raw) %line {
					%raw
				}

				XX,
			$this->init,
			$this->condition,
			$this->next,
			$this->line,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->init) {
			yield $this->init;
		}
		if ($this->condition) {
			yield $this->condition;
		}
		if ($this->next) {
			yield $this->next;
		}
		yield $this->content;
	}
}
