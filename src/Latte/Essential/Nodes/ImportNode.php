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
 * {import "file"}
 */
class ImportNode extends StatementNode
{
	public ExprNode $file;


	public static function create(Tag $tag): self
	{
		$tag->expectArguments();
		$node = new self;
		$node->file = $tag->parser->parseUnquotedStringOrExpression();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $context->format(
			'$this->createTemplate(%raw, $this->params, "import")->render() %line;',
			$this->file,
			$this->line,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
	}
}
