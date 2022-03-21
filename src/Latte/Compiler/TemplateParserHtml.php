<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Compiler\Nodes\ContentNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\Nodes\Html;
use Latte\Context;
use Latte\Helpers;
use Latte\SecurityViolationException;
use Latte\Strict;


/**
 * Template parser extension for HTML.
 */
final class TemplateParserHtml
{
	use Strict;

	/** @var array<string, callable(Tag, self): (Node|\Generator|void)> */
	private array $attrParsers = [];
	private ?Html\ElementNode $element = null;
	private TemplateParser $parser;
	private ?int $depth = null;


	public function __construct(TemplateParser $parser, array $attrParsers)
	{
		$this->parser = $parser;
		$this->attrParsers = array_reverse($attrParsers, true);
	}


	public function getElement(): ?Html\ElementNode
	{
		return $this->element;
	}


	public function parseInText(): ?Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->current();
		return match ($token->type) {
			Token::Html_TagOpen => $stream->peek(1)->is(Token::Slash)
				? $this->parseEndTag()
				: $this->parseElement(),
			Token::Html_CommentOpen => $this->parseComment(),
			Token::Html_BogusOpen => $this->parseBogusTag(),
			default => $this->parser->parseInText(),
		};
	}


	public function parseInTag(): ?Node
	{
		$token = $this->parser->getStream()->current();
		return match ($token->type) {
			Token::Html_Name => str_starts_with($token->text, TemplateLexer::NPrefix)
				? $this->parseNAttribute() // TODO: ne uvnitr bogusu
				: $this->parseAttribute(),
			Token::Quote => $this->parseAttributeQuote(),
			Token::Whitespace => $this->parseAttributeWhitespace(),
			Token::Html_TagClose, Token::Slash => null,
			default => $this->parser->parseInText(),
		};
	}


	public function parseInAttrValue(): ?Node
	{
		$token = $this->parser->getStream()->current();
		return match ($token->type) {
			Token::Quote => $this->parseAttributeQuote(),
			Token::Whitespace => $this->parseAttributeWhitespace(),
			default => $this->parser->parseInText(),
		};
	}


	private function parseElement(): Node
	{
		[$prevDepth, $this->depth] = [$this->depth, $this->parser->getTagDepth()];
		$res = $elem = $this->parseTag($this->element);

		$void = $this->resolveVoidness($elem);
		if ($elem->nAttrs) {
			$res = $this->applyNAttributes($elem, $elem->nAttrs, $void);

		} elseif (!$void) {
			$content = $this->parser->parseFragment([$this, 'parseInText']);
			if ($this->isClosingTag($elem->name)) {
				$elem->content = $content;
				$foo = $this->parseTag();
				$elem->endIndentation = $foo->indentation;
				$elem->endNewline = $foo->newline;
			} else { // element collapsed to tags
				$res = new FragmentNode([$elem, $content]);
			}
		}

		$this->element = $elem->parent;
		$this->depth = $prevDepth;
		return $res;
	}


	private function parseTag(&$node = null): Html\ElementNode
	{
		$stream = $this->parser->getStream();
		$prev = $stream->peek(-1);
		$open = $stream->consume(Token::Html_TagOpen);
		$stream->tryConsume(Token::Slash);
		$this->parser->location = $this->parser::LocationTag;
		$node = new Html\ElementNode(
			name: $stream->consume(Token::Html_Name)->text,
			indentation: !$prev || str_ends_with($prev->text, "\n")
				? strstr($open->text, '<', true)
				: null,
			line: $open->line,
			parent: $this->element,
		);
		$node->attrs = $this->parser->parseFragment([$this, 'parseInTag']);
		$node->selfClosing = (bool) $stream->tryConsume(Token::Slash);
		$node->newline = $stream->consume(Token::Html_TagClose)->text[-1] === "\n";
		$this->parser->location = $this->parser::LocationText;
		return $node;
	}


	private function parseEndTag(): ?Html\BogusTagNode
	{
		if ($this->parser->getTagDepth() === $this->depth) {
			return null; // go back to parseElement()
		}
		$stream = $this->parser->getStream();
		$token = $stream->consume(Token::Html_TagOpen);
		$this->parser->location = $this->parser::LocationTag;
		$node = new Html\BogusTagNode(
			openDelimiter: $token->text . $stream->consume(Token::Slash)->text . $stream->tryConsume(Token::Text)?->text,
			content: $this->parser->parseFragment([$this, 'parseInTag']),
			endDelimiter: $stream->consume(Token::Html_TagClose)->text,
			line: $token->line,
		);
		$this->parser->location = $this->parser::LocationText;
		return $node;
	}


	private function parseBogusTag(): Html\BogusTagNode
	{
		$stream = $this->parser->getStream();
		$token = $stream->consume(Token::Html_BogusOpen);
		$this->parser->location = $this->parser::LocationTag;
		$content = $this->parser->parseFragment(fn() => match ($stream->current()->type) {
			Token::Html_TagClose => null,
			default => $this->parser->parseInText(),
		});
		$this->parser->location = $this->parser::LocationText;
		return new Html\BogusTagNode(
			openDelimiter: $token->text,
			content: $content,
			endDelimiter: $stream->consume(Token::Html_TagClose)->text,
			line: $token->line,
		);
	}


	private function resolveVoidness(Html\ElementNode $elem): bool
	{
		if ($this->parser->getContentType() !== Context::Html) {
			return $elem->selfClosing;
		} elseif (isset(Helpers::$emptyElements[strtolower($elem->name)])) {
			return true;
		} elseif ($elem->selfClosing) { // auto-correct
			$elem->content = new Nodes\NopNode;
			$elem->endNewline = $elem->newline;
			$elem->newline = false;
			$elem->selfClosing = false;
			return true;
		}

		return $elem->selfClosing;
	}


	private function parseAttributeWhitespace(): Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->consume(Token::Whitespace);
		return $stream->is(Token::Html_Name) && str_starts_with($stream->current()->text, TemplateLexer::NPrefix)
			? new Nodes\NopNode
			: new Nodes\TextNode($token->text);
	}


	private function parseAttribute(): Node
	{
		$stream = $this->parser->getStream();
		$nameToken = $stream->consume(Token::Html_Name);
		$save = $stream->getIndex();
		$this->consumeIgnored();

		$value = null;
		if ($stream->tryConsume(Token::Equals)) {
			$this->consumeIgnored();
			if ($stream->is(Token::Quote)) {
				$value = $this->parseAttributeQuote();
			} elseif ($stream->is(Token::Latte_TagOpen) && !$stream->peek(1)->is(Token::Slash)) {
				$value = $this->parser->parseFragment([$this, 'parseInAttrValue']); // TODO: limit to first item
			} elseif ($token = $stream->tryConsume(Token::Html_Name)) {
				$value = new Nodes\TextNode($token->text);
			} else {
				$stream->throwUnexpectedException();
			}
		} else {
			$stream->seek($save);
		}

		return new Html\AttributeNode(
			name: $nameToken->text,
			value: $value,
			line: $nameToken->line,
		);
	}


	private function parseNAttribute(): Nodes\NopNode
	{
		$stream = $this->parser->getStream();
		$nameToken = $stream->consume(Token::Html_Name);
		$save = $stream->getIndex();
		$name = substr($nameToken->text, strlen(TemplateLexer::NPrefix));
		if ($this->parser->getTagDepth() !== $this->depth) {
			throw new CompileException("Attribute n:$name must not appear inside {tags}", $nameToken->line, $nameToken->column);

		} elseif (isset($this->element->nAttrs[$name])) {
			throw new CompileException("Found multiple attributes n:$name.", $nameToken->line, $nameToken->column);
		}

		$this->consumeIgnored();
		if ($stream->tryConsume(Token::Equals)) {
			$this->consumeIgnored();
			if ($stream->tryConsume(Token::Quote)) {
				$value = $stream->tryConsume(Token::Text);
				$stream->consume(Token::Quote);
			} else {
				$value = $stream->consume(Token::Html_Name);
			}
		} else {
			$value = null;
			$stream->seek($save);
		}

		$this->element->nAttrs[$name] = new Tag(
			name: preg_replace('~(inner-|tag-|)~', '', $name),
			args: $value?->text ?? '',
			line: $nameToken->line,
			prefix: match (true) {
				str_starts_with($name, 'inner-') => Tag::PrefixInner,
				str_starts_with($name, 'tag-') => Tag::PrefixTag,
				default => Tag::PrefixNone,
			},
			location: $this->parser->location,
			htmlElement: $this->element,
		);
		return new Nodes\NopNode;
	}


	private function parseAttributeQuote(): Html\QuotedValue
	{
		$stream = $this->parser->getStream();
		$quote = $stream->consume(Token::Quote);
		$value = $this->parser->parseFragment(fn() => match ($stream->current()->type) {
			Token::Quote => null,
			default => $this->parser->parseInText(),
		});
		$stream->consume(Token::Quote);
		return new Html\QuotedValue(
			value: $value,
			quote: $quote->text,
			line: $quote->line,
		);
	}


	private function parseComment(): Html\CommentNode
	{
		$this->parser->location = $this->parser::LocationTag;
		$stream = $this->parser->getStream();
		$node = new Html\CommentNode(
			line: $stream->consume(Token::Html_CommentOpen)->line,
			content: $this->parser->parseFragment(fn() => match ($stream->current()->type) {
				Token::Html_CommentClose => null,
				default => $this->parser->parseInText(),
			}),
		);
		$stream->consume(Token::Html_CommentClose);
		$this->parser->location = $this->parser::LocationText;
		return $node;
	}


	private function consumeIgnored(): void
	{
		$stream = $this->parser->getStream();
		do {
			if ($stream->tryConsume(Token::Whitespace)) {
				continue;
			}
			if ($stream->tryConsume(Token::Latte_CommentOpen)) {
				$stream->consume(Token::Text);
				$stream->consume(Token::Latte_CommentClose);
				continue;
			}
			return;
		} while (true);
	}


	private function isClosingTag(string $name): bool
	{
		$stream = $this->parser->getStream();
		return $stream->is(Token::Html_TagOpen)
			&& $stream->peek(1)->is(Token::Slash)
			&& strcasecmp($name, $stream->peek(2)->text ?? '') === 0;
	}


	private function applyNAttributes(Html\ElementNode $elem, array $nAttrs, bool $void): Node
	{
		$attrs = $this->sortNAttributes($nAttrs, $void);
		$outer = $this->openNAttrNodes($attrs[Tag::PrefixNone] ?? []);
		if ($void) {
			return $this->finishNAttrNodes($elem, $outer);
		}

		if ($attrs[Tag::PrefixTag] ?? null) {
			$elem->specialTag = true;
			$elem->tagNode = $this->finishNAttrNodes($elem->tagNode, $this->openNAttrNodes($attrs[Tag::PrefixTag]));
		}

		$inner = $this->openNAttrNodes($attrs[Tag::PrefixInner] ?? []);
		$elem->content = $this->finishNAttrNodes($this->parser->parseFragment([$this, 'parseInText']), $inner);

		if (!$this->isClosingTag($elem->name)) {
			$this->parser->getStream()->throwUnexpectedException(
				addendum: ", expecting </{$elem->name}> for element started on line {$elem->line}",
			);
		}

		$foo = $this->parseTag();
		$elem->endIndentation = $foo->indentation;
		$elem->endNewline = $foo->newline;
		return $this->finishNAttrNodes($elem, $outer);
	}


	private function sortNAttributes(array $attrs, bool $void): array
	{
		$res = [];
		foreach ($this->attrParsers as $name => $parser) {
			if ($tag = $attrs[$name] ?? null) {
				$prefix = substr($name, 0, (int) strpos($name, '-'));
				if (!$prefix || !$void) {
					$res[$prefix][] = $tag;
					unset($attrs[$name]);
				}
			}
		}

		if ($attrs) {
			$hint = Helpers::getSuggestion(array_keys($this->attrParsers), $k = key($attrs));
			throw new CompileException('Unexpected attribute n:'
				. ($hint ? "$k, did you mean n:$hint?" : implode(' and n:', array_keys($attrs))), $this->element->line);
		}

		return $res;
	}


	/**
	 * @param  array<Tag>  $toOpen
	 * @return array<array{\Generator, Tag}>
	 */
	private function openNAttrNodes(array $toOpen): array
	{
		$toClose = [];
		foreach ($toOpen as $tag) {
			$parser = $this->getTagParser($tag->name, $tag->line);
			$this->parser->pushTag($tag);
			$gen = $parser($tag, $this->parser);
			if ($gen instanceof \Generator) {
				if ($gen->valid()) {
					$toClose[] = [$gen, $tag];
					continue;
				}
			} elseif (!$gen) {
				$this->parser->popTag();
				continue;
			}

			throw new CompileException("Unexpected value returned by {$tag->getNotation()} parser.", $tag->line);
		}

		return $toClose;
	}


	private function finishNAttrNodes(ContentNode $node, array $toClose): ContentNode
	{
		while ([$gen, $tag] = array_pop($toClose)) {
			$gen->send([$node, null]);
			$node = $gen->getReturn();
			$node->line = $tag->line;
			$this->parser->popTag();
		}

		return $node;
	}


	/** @return callable(Tag, self): (Node|\Generator|void) */
	private function getTagParser(string $name, int $line): callable
	{
		if (!isset($this->attrParsers[$name])) {
			$hint = ($t = Helpers::getSuggestion(array_keys($this->attrParsers), $name))
				? ", did you mean n:$t?"
				: '';
			throw new CompileException("Unknown n:{$name}{$hint}", $line);
		} elseif (!$this->parser->isTagAllowed($name)) {
			throw new SecurityViolationException("Attribute n:$name is not allowed.");
		}
		return $this->attrParsers[$name];
	}
}
