<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\MacroTokens;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;


/**
 * {if $cond} & {elseif $cond} & {else}
 * {if} & {/if $cond}
 * {ifset $var} & {elseifset $var}
 * {ifset block} & {elseifset block}
 */
class IfNode extends StatementNode
{
	public LegacyExprNode $condition;
	public AreaNode $then;
	public ?AreaNode $else = null;
	public ?int $elseLine = null;
	public bool $capture = false;
	public bool $ifset = false;


	/** @return \Generator<int, ?array, array{AreaNode, ?Tag}, self> */
	public static function create(Tag $tag, TemplateParser $parser): \Generator
	{
		$node = new self;
		$node->ifset = in_array($tag->name, ['ifset', 'elseifset'], true);
		$node->capture = $tag->name === 'if' && $tag->args === '';
		$node->line = $tag->line;
		if (!$node->capture) {
			$node->condition = $node->ifset
				? new LegacyExprNode(self::buildCondition($tag))
				: $tag->getArgs();
		}

		[$node->then, $nextTag] = yield $node->capture ? ['else'] : ['else', 'elseif', 'elseifset'];

		if ($nextTag?->name === 'else') {
			if ($nextTag->args !== '' && str_starts_with($nextTag->args, 'if')) {
				throw new CompileException('Arguments are not allowed in {else}, did you mean {elseif}?', $nextTag->line);
			}
			$nextTag->expectArguments(false);
			$node->elseLine = $nextTag->line;
			[$node->else, $nextTag] = yield;

		} elseif ($nextTag?->name === 'elseif' || $nextTag?->name === 'elseifset') {
			if ($node->capture) {
				throw new CompileException('Tag ' . $nextTag->getNotation() . ' is unexpected here.', $nextTag->line);
			}
			$node->else = yield from self::create($nextTag, $parser);
		}

		if ($node->capture) {
			$node->condition = $nextTag->getArgs();
		}

		return $node;
	}


	private static function buildCondition(Tag $tag): string
	{
		$writer = new PhpWriter(null);
		while ([$name, $block] = $tag->tokenizer->fetchWordWithModifier(['block', '#'])) {
			$list[] = $block || preg_match('~\w[\w-]*$~DA', $name)
				? '$this->hasBlock(' . $writer->formatWord($name) . ')'
				: 'isset(' . $writer->formatArgs(new MacroTokens($name)) . ')';
		}

		return implode(' && ', $list);
	}


	public function print(PrintContext $context): string
	{
		return $this->capture
			? $this->printCapturing($context)
			: $this->printCommon($context);
	}


	private function printCommon(PrintContext $context): string
	{
		if ($this->else) {
			return $context->format(
				($this->else instanceof self
					? "if (%raw) %line { %raw } else%raw\n"
					: "if (%raw) %line { %raw } else %4.line { %3.raw }\n"),
				$this->condition,
				$this->line,
				$this->then,
				$this->else,
				$this->elseLine,
			);
		}
		return $context->format(
			"if (%raw) %line { %raw }\n",
			$this->condition,
			$this->line,
			$this->then,
		);
	}


	private function printCapturing(PrintContext $context): string
	{
		if ($this->else) {
			return $context->format(
				<<<'XX'
					ob_start(fn() => '') %line;
					try {
						%raw
						ob_start(fn() => '') %line;
						try {
							%raw
						} finally {
							$ʟ_ifB = ob_get_clean();
						}
					} finally {
						$ʟ_ifA = ob_get_clean();
					}
					echo (%raw) ? $ʟ_ifA : $ʟ_ifB %0.line;


					XX,
				$this->line,
				$this->then,
				$this->elseLine,
				$this->else,
				$this->condition,
			);
		}

		return $context->format(
			<<<'XX'
				ob_start(fn() => '') %line;
				try {
					%raw
				} finally {
					$ʟ_ifA = ob_get_clean();
				}
				if (%raw) %0.line { echo $ʟ_ifA; }

				XX,
			$this->line,
			$this->then,
			$this->condition,
		);
	}


	public function &getIterator(): \Generator
	{
		yield $this->condition;
		yield $this->then;
		if ($this->else) {
			yield $this->else;
		}
	}
}
