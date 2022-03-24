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
 * {debugbreak [$cond]}
 */
class DebugbreakNode extends StatementNode
{
	public ?LegacyExprNode $condition;


	public static function create(Tag $tag): self
	{
		$node = new self;
		$node->condition = $tag->getArgs();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		if (function_exists($func = 'debugbreak') || function_exists($func = 'xdebug_break')) {
			return $context->format(
				($this->condition ? 'if (%1.raw) ' : '') . $func . '() %0.line;',
				$this->line,
				$this->condition,
			);
		}
		return '';
	}


	public function &getIterator(): \Generator
	{
		if ($this->condition) {
			yield $this->condition;
		}
	}
}
