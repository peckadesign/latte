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
 * {breakIf ...}
 * {continueIf ...}
 * {skipIf ...}
 */
class SkipNode extends StatementNode
{
	public string $type;
	public ExprNode $condition;
	public ?string $endTag = null;


	public static function create(Tag $tag): self
	{
		$tag->expectArguments();
		if (!$tag->closest($tag->name === 'skipIf' ? ['foreach'] : ['for', 'foreach', 'while'])) {
			throw new CompileException("Tag {{$tag->name}} is unexpected here.", $tag->line);
		}

		$node = new self;
		$node->type = $tag->name;
		$node->condition = $tag->parser->parseExpression();
		if (isset($tag->htmlElement->nAttrs['foreach'])) {
			$node->endTag = $tag->htmlElement->name;
		}
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$cmd = $this->type === 'skipIf'
			? '{ $iterator->skipRound(); continue; }'
			: str_replace('If', '', $this->type) . ';';

		if ($this->endTag) {
			$cmd = "{ echo \"</$this->endTag>\\n\"; $cmd; } ";
		}

		return $context->format(
			"if (%raw) %line %raw\n",
			$this->condition,
			$this->line,
			$cmd,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
	}
}
