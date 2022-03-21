<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Context;
use Latte\Helpers;
use Latte\Policy;
use Latte\SecurityViolationException;
use Latte\Strict;


final class TemplateParser
{
	use Strict;

	public const
		LocationHead = 1,
		LocationText = 2,
		LocationTag = 3;

	public int $location = self::LocationHead;

	/** @var array<string, callable(Tag, self): (Node|\Generator|void)> */
	private array $tagParsers = [];

	/** @var array<string, callable(Tag, self): (Node|\Generator|void)> */
	private array $attrParsers = [];

	private TemplateParserHtml $html;
	private ?TokenStream $stream = null;
	private ?Policy $policy = null;
	private string $contentType = Context::Html;
	private int $tagDepth = 0;
	private array $filters = [];
	private ?Tag $tag = null;
	private $resolver;


	/** @param  array<string, callable(Tag, self): (Node|\Generator|void)>  $parsers */
	public function addTags(array $parsers): static
	{
		foreach ($parsers as $name => $parser) {
			if (str_starts_with($name, 'n:')) {
				$this->attrParsers[substr($name, 2)] = $parser;
			} else {
				$this->tagParsers[$name] = $parser;
				if (Helpers::toReflection($parser)->isGenerator()) {
					$this->attrParsers[$name] = $parser;
					$this->attrParsers[Tag::PrefixInner . '-' . $name] = $parser;
					$this->attrParsers[Tag::PrefixTag . '-' . $name] = $parser;
				}
			}
		}

		return $this;
	}


	/**
	 * Parses tokens to nodes.
	 * @throws CompileException
	 */
	public function parse(string $template, TemplateLexer $lexer): Nodes\TemplateNode
	{
		$this->html = new TemplateParserHtml($this, $this->attrParsers);
		$this->stream = new TokenStream($lexer->tokenize($template, $this->contentType));

		$node = new Nodes\TemplateNode;
		$node->main = $this->parseFragment([$this->html, 'inTextResolve']);
		if ($this->stream->current()) {
			$this->stream->throwUnexpectedException();
		}

		return $node;
	}


	public function parseFragment(callable $resolver): FragmentNode
	{
		$res = new FragmentNode;
		$prev = $this->resolver;
		$this->resolver = $resolver;
		while ($this->stream->current()) {
			if ($node = $resolver()) {
				$res->append($node);
			} else {
				break;
			}
		}

		$this->resolver = $prev;
		return $res;
	}


	public function inTextResolve(): ?Node
	{
		$token = $this->stream->current();
		return match ($token->type) {
			LegacyToken::TEXT => $this->parseText(),
			LegacyToken::COMMENT => $this->parseLatteComment(),
			LegacyToken::MACRO_TAG => $this->parseLatteMarkup(),
			default => null,
		};
	}


	private function parseText(): Nodes\TextNode
	{
		$token = $this->stream->consume(LegacyToken::TEXT);
		if ($this->location === self::LocationHead && trim($token->text) !== '') {
			$this->location = self::LocationText;
		}
		return new Nodes\TextNode($token->text, $token->line);
	}


	private function parseLatteComment(): Node
	{
		$token = $this->stream->consume(LegacyToken::COMMENT);
		if ($token->indentation === null && $token->newline) {
			return new Nodes\TextNode("\n");
		}
		return new Nodes\NopNode;
	}


	private function parseLatteMarkup(): ?Node
	{
		$token = $this->stream->current();

		if ($token->closing
			|| (isset($this->filters[$this->tagDepth]) && in_array($token->name, $this->filters[$this->tagDepth], true))
		) {
			return null;

		} else {
			return $this->parseLatteStatement();
		}
	}


	private function parseLatteStatement(): Node
	{
		$token = $endToken = $this->stream->consume(LegacyToken::MACRO_TAG);
		$tag = $this->pushTag($this->createTag($token));
		$this->tagDepth++;

		$parser = $this->getTagParser($tag->name, $tag->line);
		$res = $parser($tag, $this);
		if ($res instanceof \Generator) {
			while ($res->valid()) {
				if ($tag->void) {
					$res->send([new FragmentNode, $tag]);
					break;
				}

				$this->filters[$this->tagDepth] = $res->current() ?: null;
				$content = $this->parseFragment($this->resolver);
				$endTag = ($endToken = $this->stream->tryConsume(LegacyToken::MACRO_TAG))
					? $this->pushTag($this->createTag($endToken))
					: null;

				$res->send([$content, $endTag]);

				if ($endTag) {
					$this->popTag();
					if ($endTag->closing) {
						break;
					}
				}
			}

			if ($res->valid()) {
				throw new CompileException("Unexpected behaviour by {{$tag->name}} parser.", $tag->line);
			} elseif ($token !== $endToken) {
				$this->checkEndTag($tag, $endTag);
			}

			unset($this->filters[$this->tagDepth]);
			$node = $res->getReturn();

		} else {
			if ($token->empty) {
				throw new CompileException("Unexpected /} in tag {$token->text}", $token->line);
			}

			$node = $res;
		}

		if (!$node instanceof Node) {
			throw new CompileException("Unexpected value returned by {{$tag->name}} parser.", $tag->line);
		}

		$outputMode = $node instanceof Nodes\StatementNode
			? $node->getOutputMode()
			: null;
		if ($outputMode !== Nodes\StatementNode::OutputNone && $this->location === self::LocationHead) {
			$this->location = self::LocationText;
		}

		$this->tagDepth--;
		$this->popTag();

		$node->line = $token->line;
		$replaced = $outputMode === null || $outputMode === Nodes\StatementNode::OutputInline;
		$res = new FragmentNode;
		if ($token->indentation && ($replaced || !$token->newline)) {
			$res->append(new Nodes\TextNode($token->indentation));
		}

		$res->append($node);

		if ($endToken?->newline && ($replaced || $endToken?->indentation === null)) {
			$res->append(new Nodes\TextNode("\n"));
		}

		return $res;
	}


	private function createTag(LegacyToken $token): Tag
	{
		$modifiers = $token->modifiers;

		if (strpbrk($token->name, '=~%^&_')) {
			if (!Helpers::removeFilter($modifiers, 'noescape')) {
				$modifiers .= '|escape';
			} elseif ($this->policy && !$this->policy->isFilterAllowed('noescape')) {
				throw new SecurityViolationException('Filter |noescape is not allowed.');
			}

			if (
				$token->name === '='
				&& $this->html->getElement()
				&& ($prev = $this->stream->peek(-1))
				&& $this->contentType === Context::Html
				&& strcasecmp($this->html->getElement()->startTag->getName(), 'script') === 0
				&& preg_match('#["\']$#D', $prev->text)
			) {
				throw new CompileException("Do not place {$token->text} inside quotes in JavaScript.", $token->line);
			}
		}

		return new Tag($token->name, $token->value, $modifiers, $token->empty, $token->closing, $token->line, $this->location, $this->html->getElement());
	}


	/** @return callable(Tag, self): (Node|\Generator|void) */
	private function getTagParser(string $name, int $line): callable
	{
		if (!isset($this->tagParsers[$name])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->tagParsers), $name))
				? ", did you mean {{$t}}?"
				: '';
			if ($this->contentType === Context::Html
				&& in_array($this->html->getElement()?->name, ['script', 'style'], true)
			) {
				$hint .= ' (in JavaScript or CSS, try to put a space after bracket or use n:syntax=off)';
			}
			throw new CompileException("Unexpected tag {{$name}}$hint", $line);
		} elseif (!$this->isTagAllowed($name)) {
			throw new SecurityViolationException("Tag {{$name}} is not allowed.");
		}

		return $this->tagParsers[$name];
	}


	private function checkEndTag(Tag $start, ?Tag $end): void
	{
		if (!$end
			|| ($end->name !== $start->name && $end->name !== '')
			|| !$end->closing
			|| $end->modifiers
			|| ($end->args !== '' && $start->args !== '' && !str_starts_with($start->args . ' ', $end->args . ' '))
		) {
			$tag = $end?->getNotation($end->args !== '') ?? 'end';
			throw new CompileException("Unexpected $tag, expecting {/$start->name}", ($end ?? $start)->line);
		}
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		return $this;
	}


	public function getContentType(): string
	{
		return $this->contentType;
	}


	/** @internal */
	public function getStream(): TokenStream
	{
		return $this->stream;
	}


	public function getTagDepth(): int
	{
		return $this->tagDepth;
	}


	public function pushTag(Tag $tag): Tag
	{
		$tag->parent = $this->tag;
		$this->tag = $tag;
		return $tag;
	}


	public function popTag(): void
	{
		$this->tag = $this->tag->parent;
	}


	public function isTagAllowed(string $name): bool
	{
		return !$this->policy || $this->policy->isTagAllowed($name);
	}
}
