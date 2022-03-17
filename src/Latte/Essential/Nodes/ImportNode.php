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
 * {import "file"}
 */
class ImportNode extends StatementNode
{
	public LegacyExprNode $file;
	public bool $global = false;


	public static function create(Tag $tag): self
	{
		$tag->expectArguments();
		$node = new self;
		$node->file = $tag->getWord();
		$node->global = $tag->isInHead();
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$code = $context->format(
			'$this->createTemplate(%word, $this->params, "import")->render() %line;',
			$this->file,
			$this->line,
		);
		if ($this->global) {
			$context->addInitialization($code);
			return '';
		}
		return $code;
	}


	public function &getIterator(): \Generator
	{
		yield $this->file;
	}
}
