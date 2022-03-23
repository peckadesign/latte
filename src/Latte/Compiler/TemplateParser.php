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
use Latte\Runtime\Template;
use Latte\SecurityViolationException;
use Latte\Strict;


final class TemplateParser
{
	use Strict;

	public const
		LocationHead = 1,
		LocationText = 2,
		LocationTag = 3;

	/** @var Block[][] */
	public array $blocks = [[]];
	public int $blockLayer = Template::LayerTop;
	public int $location = self::LocationHead;

	/** @var array<string, callable(Tag, self): (Node|\Generator|void)> */
	private array $tagParsers = [];

	/** @var array<string, callable(Tag, self): (Node|\Generator|void)> */
	private array $attrParsers = [];

	private TemplateParserHtml $html;
	private ?TokenStream $stream = null;
	private ?TemplateLexer $lexer = null;
	private ?Policy $policy = null;
	private string $contentType = Context::Html;
	private int $tagDepth = 0;
	private int $counter = 0;
	private array $filters = [];
	private ?Tag $tag = null;
	private $context;


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
	public function parse(string $template, TemplateLexer $lexer): Node
	{
		$this->lexer = $lexer;
		$this->html = new TemplateParserHtml($this, $this->attrParsers);
		$this->stream = new TokenStream($lexer->tokenize($template, $this->contentType));

		$node = $this->parseFragment([$this->html, 'parseInText']);
		if ($this->stream->current()) {
			$this->stream->throwUnexpectedException();
		}
		return $node;
	}


	public function parseFragment(callable $context): FragmentNode
	{
		$res = new FragmentNode;
		$prev = $this->context;
		$this->context = $context;
		while ($this->stream->current()) {
			if ($node = $context()) {
				$res->append($node);
			} else {
				break;
			}
		}

		$this->context = $prev;
		return $res;
	}


	public function parseInText(): ?Node
	{
		$token = $this->stream->current();
		return match ($token->type) {
			Token::Text => $this->parseText(),
			Token::Latte_CommentOpen => $this->parseLatteComment(),
			Token::Latte_TagOpen => $this->stream->peek(1)->is(Token::Slash)
				? null // TODO: error uvnitr HTML?
				: $this->parseLatteStatement(),
			default => null,
		};
	}


	private function parseText(): Nodes\TextNode
	{
		$token = $this->stream->consume(Token::Text);
		if ($this->location === self::LocationHead && trim($token->text) !== '') {
			$this->location = self::LocationText;
		}
		return new Nodes\TextNode($token->text, $token->line);
	}


	private function parseLatteComment(): Node
	{
		$stream = $this->stream;
		$leftMost = !($prev = $stream->peek(-1)) || str_ends_with($prev->text, "\n");
		$stream->consume(Token::Latte_CommentOpen);
		$stream->consume(Token::Text);
		$token = $stream->consume(Token::Latte_CommentClose);
		if (!$leftMost && $token->text[-1] === "\n") {
			return new Nodes\TextNode("\n");
		}
		return new Nodes\NopNode;
	}


	public function parseLatteStatement(): ?Node
	{
		if (isset($this->filters[$this->tagDepth])
			&& in_array($this->stream->peek(1)->text, $this->filters[$this->tagDepth], true)
		) {
			return null; // go back to previous parseLatteStatement()
		}

		$endTag = $tag = $this->pushTag($this->parseLatteTag());
		$checkEnds[] = $tag;
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
				$content = $this->parseFragment($this->context);
				$endTag = $this->stream->is(Token::Latte_TagOpen)
					? $this->pushTag($this->parseLatteTag())
					: null;

				$res->send([$content, $endTag]);

				if ($endTag) {
					$this->popTag();
					$checkEnds[] = $endTag;
					if ($endTag->closing) {
						break;
					}
				}
			}

			if ($res->valid()) {
				throw new CompileException("Unexpected behaviour by {{$tag->name}} parser.", $tag->line);
			} elseif ($tag !== $endTag) {
				$this->checkEndTag($tag, $endTag);
			}

			unset($this->filters[$this->tagDepth]);
			$node = $res->getReturn();

		} else {
			if ($tag->void) {
				throw new CompileException('Unexpected /} in tag ' . substr($tag->getNotation(true), 0, -1) . '/}', $tag->line);
			}

			$node = $res;
		}

		if (!$node instanceof Node) {
			throw new CompileException("Unexpected value returned by {{$tag->name}} parser.", $tag->line);
		}

		foreach ($checkEnds as $tmp) {
			$tmp->expectEnd();
		}

		$outputMode = $node instanceof Nodes\StatementNode
			? $node->getOutputMode()
			: null;
		if ($outputMode !== Nodes\StatementNode::OutputNone && $this->location === self::LocationHead) {
			$this->location = self::LocationText;
		}

		$this->tagDepth--;
		$this->popTag();

		$node->line = $tag->line;
		$replaced = $outputMode === null || $outputMode === Nodes\StatementNode::OutputInline;
		$res = new FragmentNode;
		if ($tag->indentation && ($replaced || !$tag->newline)) {
			$res->append(new Nodes\TextNode($tag->indentation));
		}

		$res->append($node);

		if ($endTag?->newline && ($replaced || $endTag?->indentation === null)) {
			$res->append(new Nodes\TextNode("\n"));
		}

		return $res;
	}


	private function parseLatteTag(): Tag
	{
		$stream = $this->stream;
		$prev = $stream->peek(-1);
		$open = $stream->consume(Token::Latte_TagOpen);
		return new Tag(
			line: $open->line,
			closing: $c = (bool) $stream->tryConsume(Token::Slash),
			name: $stream->tryConsume(Token::Latte_Name)?->text ?? ($c ? '' : '='),
			tokens: $this->consumeTag(),
			void: (bool) $stream->tryConsume(Token::Slash),
			indentation: !$prev || str_ends_with($prev->text, "\n")
				? strstr($open->text, '{', true)
				: null,
			newline: $stream->consume(Token::Latte_TagClose)->text[-1] === "\n",
			location: $this->location,
			htmlElement: $this->html->getElement(),
		);
	}


	private function consumeTag(): array
	{
		$res = [];
		do {
			$token = $this->stream->peek(0);
			if (!$token?->isPhpKind()) {
				return $res;
			}
			$res[] = $this->stream->consume();
		} while (true);
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
		if ($start->name === 'syntax'
			|| $start->name === 'block' && !$this->tag->parent) { // TODO: hardcoded
			return;
		}

		if (!$end
			|| ($end->name !== $start->name && $end->name !== '')
			|| !$end->closing
			|| $end->void
		) {
			$tag = $end?->getNotation() ?? 'end';
			throw new CompileException("Unexpected $tag, expecting {/$start->name}", ($end ?? $start)->line);
		}
	}


	public function checkBlockIsUnique(Block $block): void
	{
		if ($block->isDynamic() || !preg_match('#^[a-z]#iD', $name = (string) $block->name->value)) {
			throw new CompileException(ucfirst($block->tag->name) . " name must start with letter a-z, '$name' given.", $block->tag->line);
		}

		if ($block->layer === Template::LayerSnippet
			? isset($this->blocks[$block->layer][$name])
			: (isset($this->blocks[Template::LayerLocal][$name]) || isset($this->blocks[$this->blockLayer][$name]))
		) {
			throw new CompileException("Cannot redeclare {$block->tag->name} '{$name}'", $block->tag->line);
		}

		$this->blocks[$block->layer][$name] = $block;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		$this->lexer?->setContentType($type);
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


	public function getLexer(): TemplateLexer
	{
		return $this->lexer;
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


	public function generateId(): int
	{
		return $this->counter++;
	}


	public function isTagAllowed(string $name): bool
	{
		return !$this->policy || $this->policy->isTagAllowed($name);
	}
}
