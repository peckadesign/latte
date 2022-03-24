<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {templatePrint [ClassName]}
 */
class TemplatePrintNode extends StatementNode
{
	public ?string $template;


	public static function create(Tag $tag): self
	{
		$node = new self;
		$node->template = $tag->tokenizer->fetchWord() ?: null;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$context->initialization .= '(new Latte\Essential\Blueprint)->printClass($this, ' . var_export($this->template, true) . '); exit;';
		return '';
	}
}
