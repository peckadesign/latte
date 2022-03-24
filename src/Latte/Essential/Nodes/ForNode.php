<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {for $init; $cond; $next}
 */
class ForNode extends StatementNode
{
	public LegacyExprNode $args;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		$tag->expectArguments();
		$node = new self;
		$node->args = $tag->getArgs();
		[$node->content] = yield;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			<<<'XX'
				for (%raw) %line {
					%raw
				}

				XX,
			$this->args,
			$this->line,
			$this->content,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->args;
		yield $this->content;
	}
}
