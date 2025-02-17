<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Php\Expr\ArrayNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * {embed [block|file] name [,] [params]}
 */
class EmbedNode extends StatementNode
{
	public ExprNode $name;
	public string $mode;
	public ArrayNode $args;
	public FragmentNode $blocks;
	public int|string|null $layer;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->expectArguments();

		$node = new self;
		[$node->name, $mode] = $tag->parser->parseWithModifier(['block', 'file']);
		$node->mode = $mode ?? ($node->name instanceof StringNode && preg_match('~[\w-]+$~DA', $node->name->value) ? 'block' : 'file');
		$tag->parser->stream->tryConsume(',');
		$node->args = $tag->parser->parseArguments();

		$prevIndex = $parser->blockLayer;
		$parser->blockLayer = $node->layer = count($parser->blocks);
		$parser->blocks[$parser->blockLayer] = [];
		[$node->blocks] = yield;

		foreach ($node->blocks->children as $child) {
			if (!$child instanceof ImportNode && !$child instanceof BlockNode && !$child instanceof TextNode) {
				throw new CompileException('Unexpected content inside {embed} tags.', $child->line);
			}
		}

		$parser->blockLayer = $prevIndex;
		return $node;
	}


	public function print(PrintContext $context): string
	{
		$imports = '';
		foreach ($this->blocks->children as $child) {
			if ($child instanceof ImportNode) {
				$imports .= $child->print($context);
			} else {
				$child->print($context);
			}
		}

		return $this->mode === 'file'
			? $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					try {
						$this->createTemplate(%raw, %raw, "embed")->renderToContentType(%dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->line,
				$imports,
				$this->name,
				$this->args,
				implode('', $context->getEscapingContext()),
			)
			: $context->format(
				<<<'XX'
					$this->enterBlockLayer(%dump, get_defined_vars()) %line; %raw
					$this->copyBlockLayer();
					try {
						$this->renderBlock(%raw, %raw, %dump) %1.line;
					} finally {
						$this->leaveBlockLayer();
					}

					XX,
				$this->layer,
				$this->line,
				$imports,
				$this->name,
				$this->args,
				implode('', $context->getEscapingContext()),
			);
	}


	public function getOutputMode(): int
	{
		return self::OutputBlock;
	}


	public function &getIterator(): \Generator
	{
		yield $this->name;
		yield $this->args;
		yield $this->blocks;
	}
}
