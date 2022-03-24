<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Block;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\Expr\AssignNode;
use Latte\Compiler\Nodes\Php\Expr\VariableNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Compiler\Token;
use Latte\Runtime\Template;


/**
 * {define [local] name}
 */
class DefineNode extends StatementNode
{
	public Block $block;
	public AreaNode $content;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$tag->expectArguments();
		[$name, $local] = $tag->parser->parseWithModifier(['local']);
		$layer = $local ? Template::LayerLocal : $parser->blockLayer;

		$node = new self;
		$node->block = new Block($name, $layer, $tag);
		if (!$node->block->isDynamic()) {
			$parser->checkBlockIsUnique($node->block);
			$tag->data->block = $node->block; // for {include}
			$tag->parser->stream->tryConsume(',');
			$node->block->parameters = self::parseParameters($tag);
		}

		[$node->content, $endTag] = yield;
		if ($endTag && $name instanceof Scalar\StringNode) {
			$endTag->parser->stream->tryConsume($name->value);
		}

		return $node;
	}


	private static function parseParameters(Tag $tag): array
	{
		$stream = $tag->parser->stream;
		$params = [];
		while ($stream->current()) {
			$tag->parser->parseType();

			$save = $stream->getIndex();
			$expr = $stream->is(Token::Php_Variable) ? $tag->parser->parseExpression() : null;
			if ($expr instanceof VariableNode && is_string($expr->name)) {
				$params[] = new AssignNode($expr, new Scalar\NullNode);
			} elseif (
				$expr instanceof AssignNode
				&& $expr->var instanceof VariableNode
				&& is_string($expr->var->name)
			) {
				$params[] = $expr;
			} else {
				$stream->seek($save);
				$stream->throwUnexpectedException(addendum: ' in ' . $tag->getNotation());
			}

			if (!$stream->tryConsume(',')) {
				break;
			}
		}

		return $params;
	}


	public function print(PrintContext $context): string
	{
		return $this->block->isDynamic()
			? $this->printDynamic($context)
			: $this->printStatic($context);
	}


	private function printStatic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added
		return '';
	}


	private function printDynamic(PrintContext $context): string
	{
		$context->addBlock($this->block);
		$this->block->content = $this->content->print($context); // must be compiled after is added

		return $context->format(
			'$this->addBlock(%raw, %dump, [[$this, %dump]], %dump);',
			new AssignNode(new VariableNode('ʟ_nm'), $this->block->name),
			implode('', $context->getEscapingContext()),
			$this->block->method,
			$this->block->layer,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->block->name;
		yield $this->content;
	}
}
