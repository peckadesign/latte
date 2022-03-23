<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * n:ifcontent
 */
class IfContentNode extends StatementNode
{
	public ContentNode $content;
	public int $id;
	public ElementNode $htmlElement;


	/** @return \Generator<int, ?array, array{FragmentNode, ?Tag}, self> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$node = new self;
		$node->id = $parser->generateId();
		[$node->content] = yield;
		$node->htmlElement = $tag->htmlElement;
		if (!$node->htmlElement->content) {
			throw new CompileException("Unnecessary n:content on empty element <{$node->htmlElement->name}>", $tag->line);
		}
		return $node;
	}


	public function print(PrintContext $context): string
	{
		try {
			$saved = $this->htmlElement->content;
			$this->htmlElement->content = new AuxiliaryNode(fn() => <<<XX
				ob_start();
				try {
					{$saved->print($context)}
				} finally {
					\$ʟ_ifc[$this->id] = rtrim(ob_get_flush()) === '';
				}

				XX);
			return <<<XX
				ob_start(fn() => '');
				try {
					{$this->content->print($context)}
				} finally {
					if (\$ʟ_ifc[$this->id] ?? null) {
						ob_end_clean();
					} else {
						echo ob_get_clean();
					}
				}

				XX;
		} finally {
			$this->htmlElement->content = $saved;
		}
	}


	public function &getIterator(): \Generator
	{
		yield $this->content;
	}
}
