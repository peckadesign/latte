<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {first [$width]}
 * {last [$width]}
 * {sep [$width]}
 */
class FirstLastSepNode extends StatementNode
{
	public string $name;
	public ?ExprNode $width;
	public ContentNode $then;
	public ?ContentNode $else = null;
	public ?int $elseLine = null;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		if (!($tag->closest(['foreach']))) {
			throw new CompileException("Tag {{$tag->name}} must be inside {foreach} ... {/foreach}.", $tag->line);
		}

		$node = new self;
		$node->name = $tag->name;
		$node->width = $tag->parser->isEnd() ? null : $tag->parser->parseExpression();

		[$node->then, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$node->elseLine = $nextTag->line;
			[$node->else] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		$cond = match ($this->name) {
			'first' => '$iterator->isFirst',
			'last' => '$iterator->isLast',
			'sep' => '!$iterator->isLast',
		};
		return $context->format(
			$this->else
				? "if ($cond(%raw)) %line { %raw } else %line { %raw }\n"
				: "if ($cond(%raw)) %line { %raw }\n",
			$this->width,
			$this->line,
			$this->then,
			$this->elseLine,
			$this->else,
		);
	}


	public function &getIterator(): \Generator
	{
		if ($this->width) {
			yield $this->width;
		}
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
