<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\LegacyExprNode;
use Latte\Strict;


/**
 * Latte tag or n:attribute.
 */
final class Tag
{
	use Strict;

	public const
		PrefixInner = 'inner',
		PrefixTag = 'tag',
		PrefixNone = '';

	public MacroTokens $tokenizer;
	public \stdClass $data;
	public ?string $modifiers = null;


	public function __construct(
		public /*readonly*/ string $name,
		public /*readonly*/ string $args,
		public /*readonly*/ bool $void = false,
		public /*readonly*/ bool $closing = false,
		public /*readonly*/ ?int $line = null,
		public /*readonly*/ int $location = 0,
		public /*readonly*/ ?ElementNode $htmlElement = null,
		public /*readonly*/ ?self $parent = null,
		public /*readonly*/ ?string $prefix = null,
		public /*readonly*/ ?string $indentation = null,
		public /*readonly*/ bool $newline = false,
	) {
		$this->data = new \stdClass;
		$this->setArgs($args);
	}


	public function isInHead(): bool
	{
		return $this->location === TemplateParser::LocationHead && !$this->parent;
	}


	public function isInText(): bool
	{
		return $this->location <= TemplateParser::LocationText;
	}


	public function isNAttribute(): bool
	{
		return $this->prefix !== null;
	}


	private function setArgs(string $args): void
	{
		$this->args = trim($args);
		$this->tokenizer = new MacroTokens($this->args);
	}


	public function getNotation(bool $withArgs = false): string
	{
		$args = $withArgs ? $this->args : '';
		return $this->isNAttribute()
			? TemplateLexer::NPrefix . ($this->prefix ? $this->prefix . '-' : '')
				. $this->name
				. ($args === '' ? '' : '="' . $args . '"')
			: '{'
				. ($this->closing ? '/' : '')
				. rtrim($this->name
				. ($args === '' ? '' : ' ' . $args))
			. '}';
	}


	/**
	 * @param  string[]  $names
	 */
	public function closest(array $names, ?callable $condition = null): ?self
	{
		$tag = $this->parent;
		while ($tag && (
			!in_array($tag->name, $names, true)
			|| ($condition && !$condition($tag))
		)) {
			$tag = $tag->parent;
		}

		return $tag;
	}


	/**
	 * @throws CompileException
	 */
	public function expectArguments(string|bool|null $arguments = true): void
	{
		if ($arguments && $this->args === '') {
			$label = is_string($arguments) ? $arguments : 'arguments';
			throw new CompileException('Missing ' . $label . ' in ' . $this->getNotation(), $this->line);

		} elseif ($arguments === false && $this->args !== '') {
			throw new CompileException('Arguments are not allowed in ' . $this->getNotation(), $this->line);
		}
	}


	public function extractModifier(): void
	{
		if (preg_match('~^
			(?<args>(?:' . TemplateLexer::ReString . '|[^\'"])*?)
			(?<modifiers>(?<!\|)\|[a-z](?<modArgs>(?:' . TemplateLexer::ReString . '|(?:\((?P>modArgs)\))|[^\'"/()]|/(?=.))*+))?
		$~Disx', $this->args, $match)) {
			$this->setArgs(trim($match['args']));
			$this->modifiers = $match['modifiers'] ?? '';
		}
	}


	public function getArgs(): ?LegacyExprNode
	{
		if ($this->args === '') {
			return null;
		}
		return new LegacyExprNode($this->tokenizer->joinAll());
	}


	public function getWord(): LegacyExprNode
	{
		return new LegacyExprNode($this->tokenizer->fetchWord());
	}
}
