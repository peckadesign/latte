<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {do $statement}
 */
class DoNode extends StatementNode
{
	public LegacyExprNode $statement;


	public static function create(Tag $tag): self
	{
		$tag->expectArguments();
		$node = new self;
		$node->statement = $tag->getArgs();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'%raw %line;',
			$this->statement,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->statement;
	}
}
