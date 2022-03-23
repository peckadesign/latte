<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Tags;

use Latte;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * {syntax ...}
 */
final class SyntaxTag
{
	use Latte\Strict;

	/**
	 * @return \Generator<int, ?array, array{FragmentNode, ?Tag}, FragmentNode>
	 */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->expectArguments();
		$parser->getLexer()->setSyntax($tag->args);
		[$inner] = yield;
		$parser->getLexer()->setSyntax(null);
		return $inner;
	}
}
