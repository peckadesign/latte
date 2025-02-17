<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\Php\Expr\ArrayNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {ifchanged [$var]} ... {else}
 */
class IfChangedNode extends StatementNode
{
	public ArrayNode $conditions;
	public AreaNode $then;
	public ?AreaNode $else = null;
	public ?int $elseLine = null;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag): \Generator
	{
		$node = new self;
		$node->conditions = $tag->parser->parseArguments();

		[$node->then, $nextTag] = yield ['else'];
		if ($nextTag?->name === 'else') {
			$node->elseLine = $nextTag->line;
			[$node->else] = yield;
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return $this->conditions->items
			? $this->printExpression($context)
			: $this->printCapturing($context);
	}


	private function printExpression(PrintContext $context): string
	{
		return $this->else
			? $context->format(
				<<<'XX'
					if (($ʟ_loc[%dump] ?? null) !== ($ʟ_tmp = %raw)) {
						$ʟ_loc[%0.dump] = $ʟ_tmp;
						%raw
					} else %line {
						%raw
					}


					XX,
				$context->generateId(),
				$this->conditions,
				$this->then,
				$this->elseLine,
				$this->else,
			)
			: $context->format(
				<<<'XX'
					if (($ʟ_loc[%dump] ?? null) !== ($ʟ_tmp = %raw)) {
						$ʟ_loc[%0.dump] = $ʟ_tmp;
						%2.raw
					}


					XX,
				$context->generateId(),
				$this->conditions,
				$this->then,
			);
	}


	private function printCapturing(PrintContext $context): string
	{
		return $this->else
			? $context->format(
				<<<'XX'
					ob_start(fn() => '');
					try %line {
						%raw
					} finally { $ʟ_tmp = ob_get_clean(); }
					if (($ʟ_loc[%dump] ?? null) !== $ʟ_tmp) {
						echo $ʟ_loc[%2.dump] = $ʟ_tmp;
					} else %line {
						%raw
					}


					XX,
				$this->line,
				$this->then,
				$context->generateId(),
				$this->elseLine,
				$this->else,
			)
			: $context->format(
				<<<'XX'
					ob_start(fn() => '');
					try %line {
						%raw
					} finally { $ʟ_tmp = ob_get_clean(); }
					if (($ʟ_loc[%dump] ?? null) !== $ʟ_tmp) {
						echo $ʟ_loc[%2.dump] = $ʟ_tmp;
					}


					XX,
				$this->line,
				$this->then,
				$context->generateId(),
			);
	}


	public function &getIterator(): \Generator
	{
		if ($this->conditions) {
			yield $this->conditions;
		}
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
