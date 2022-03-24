<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {varType type $var}
 */
class VarTypeNode extends StatementNode
{
	public static function create(Tag $tag): self
	{
		$tag->expectArguments();

		$type = trim($tag->tokenizer->joinUntil($tag->tokenizer::T_VARIABLE));
		$variable = $tag->tokenizer->nextValue($tag->tokenizer::T_VARIABLE);
		if (!$type || !$variable) {
			throw new CompileException('Unexpected content, expecting {varType type $var}.', $tag->line);
		}

		return new self;
	}


	public function print(PrintContext $context): string
	{
		return '';
	}
}
