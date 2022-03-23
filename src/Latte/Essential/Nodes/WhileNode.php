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
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {while $cond}
 */
class WhileNode extends StatementNode
{
	public LegacyExprNode $condition;
	public ContentNode $content;
	public bool $postTest;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		$node = new self;
		$node->postTest = $tag->args === '';
		if (!$node->postTest) {
			$node->condition = $tag->getArgs();
		}

		[$node->content, $nextTag] = yield;
		if ($node->postTest) {
			$nextTag->expectArguments();
			$node->condition = $nextTag->getArgs();
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $this->postTest
			? $context->format(
				<<<'XX'
					do %line {
						%raw
					} while (%args);

					XX,
				$this->line,
				$this->content,
				$this->condition,
			)
			: $context->format(
				<<<'XX'
					while (%args) %line {
						%raw
					}

					XX,
				$this->condition,
				$this->line,
				$this->content,
			);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->content;
	}
}
