<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {extends none | $var | "file"}
 * {layout none | $var | "file"}
 */
class ExtendsNode extends StatementNode
{
	public ?ExprNode $extends;


	public static function create(Tag $tag): self
	{
		$tag->expectArguments();
		$node = new self;
		if (!$tag->isInHead()) {
			throw new CompileException("{{$tag->name}} must be placed in template head.", $tag->line);
		} elseif (isset($tag->data->extends)) {
			throw new CompileException("Multiple {{$tag->name}} declarations are not allowed.", $tag->line);
		} elseif ($tag->parser->stream->tryConsume('none')) {
			$node->extends = null;
		} else {
			$node->extends = $tag->parser->parseUnquotedStringOrExpression();
		}
		$tag->data->extends = true;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $this->extends
			? $context->format('$this->parentName = %raw;', $this->extends)
			: '$this->parentName = false;';
	}


	public function &getIterator(): \Generator
	{
		if ($this->extends) {
			yield $this->extends;
		}
	}
}
