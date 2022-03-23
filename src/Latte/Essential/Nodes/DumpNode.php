<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {dump [$var]}
 */
class DumpNode extends StatementNode
{
	public ?ExprNode $expression = null;


	public static function create(Tag $tag): self
	{
		$node = new self;
		$node->expression = $tag->parser->isEnd()
			? null
			: $tag->parser->parseExpression();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			$this->expression
				? 'Tracy\Debugger::barDump((%raw), %dump) %line;'
				: 'Tracy\Debugger::barDump(get_defined_vars(), \'variables\') %2.line;',
			$this->expression,
			$this->expression?->text,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->expression) {
			yield $this->expression;
		}
	}
}
