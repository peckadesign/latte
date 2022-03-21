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
		$token = $this->parser->getStream()->current();
		return match ($token->type) {
			LegacyToken::HTML_TAG_BEGIN => $this->parseMarkup(),
			default => $this->parser->parseInText(),
		};
	}


	public function parseInTag(): ?Node
	{
		$token = $this->parser->getStream()->current();
		return match ($token->type) {
			LegacyToken::HTML_ATTRIBUTE_BEGIN => $this->parseAttribute(),
			LegacyToken::HTML_ATTRIBUTE_END => $this->parseAttributeEnd(),
			LegacyToken::HTML_TAG_END => null,
			default => $this->parser->parseInText(),
		};
	}


	private function parseMarkup(): ?Node
	{
		$token = $this->parser->getStream()->current();
		if ($token->closing && $this->parser->getTagDepth() === $this->depth) {
			return null; // go back to parseHtmlElement()

		} elseif ($token->text === '<!--') {
			return $this->parseComment();

		} elseif ($token->closing || $token->text === '<?' || $token->text === '<!') {
			return $this->parseBogusTag();

		} else {
			return $this->parseElement();
		}
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
			if ($this->isClosingTag($this->parser->getStream()->current(), $elem->name)) {
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
		$beginToken = $stream->consume(LegacyToken::HTML_TAG_BEGIN);
		$this->parser->location = $this->parser::LocationTag;
		$node = new Html\ElementNode(
			name: $beginToken->name,
			indentation: substr($beginToken->text, 0, strpos($beginToken->text, '<')),
			line: $beginToken->line,
			parent: $this->element,
		);
		$node->attrs = $this->parser->parseFragment([$this, 'parseInTag']);
		$endToken = $stream->consume(LegacyToken::HTML_TAG_END);
		$node->selfClosing = str_contains($endToken->text, '/');
		$node->newline = $endToken->text[-1] === "\n";
		$this->parser->location = $this->parser::LocationText;
		return $node;
	}


	private function parseBogusTag(): Html\BogusTagNode
	{
		$stream = $this->parser->getStream();
		$beginToken = $stream->consume(LegacyToken::HTML_TAG_BEGIN);
		$this->parser->location = $this->parser::LocationTag;
		$attrs = $this->parser->parseFragment([$this, 'parseInTag']);
		$endToken = $stream->consume(LegacyToken::HTML_TAG_END);
		$this->parser->location = $this->parser::LocationText;
		return new Html\BogusTagNode(
			openDelimiter: $beginToken->text,
			content: $attrs,
			endDelimiter: $endToken->text,
			line: $beginToken->line,
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


	private function parseAttribute(): Node
	{
		$stream = $this->parser->getStream();
		$token = $stream->consume(LegacyToken::HTML_ATTRIBUTE_BEGIN);

		if (str_starts_with($token->name, TemplateLexer::NPrefix)) {
			$name = substr($token->name, strlen(TemplateLexer::NPrefix));
			if ($this->parser->getTagDepth() !== $this->depth) {
				throw new CompileException("Attribute n:$name must not appear inside {tags}", $token->line);

			} elseif (isset($this->element->nAttrs[$name])) {
				throw new CompileException("Found multiple attributes n:$name.", $token->line);
			}

			$this->element->nAttrs[$name] = $this->createTagFromAttr($token);
			return new Nodes\NopNode;
		}

		$node = new Html\AttributeNode(
			name: $token->name,
			text: $token->text,
			quote: in_array($token->value, ['"', "'"], true) ? $token->value : null,
			line: $token->line,
		);

		if ($token->value === '"' || $token->value === "'") {
			$node->value = $this->parser->parseFragment(fn() => match ($stream->current()->type) {
				LegacyToken::HTML_ATTRIBUTE_END => null,
				default => $this->parser->parseInText(),
			});
		}

		return $node;
	}


	private function parseAttributeEnd(): Html\AttributeNode
	{
		$token = $this->parser->getStream()->consume(LegacyToken::HTML_ATTRIBUTE_END);
		return new Html\AttributeNode('', $token->text); // switches context to CONTEXT_HTML_TAG
	}


	private function parseComment(): Html\CommentNode
	{
		$stream = $this->parser->getStream();
		$this->parser->location = TemplateParser::LocationTag;
		$token = $stream->consume(LegacyToken::HTML_TAG_BEGIN);
		$node = new Html\CommentNode($this->parser->parseFragment(fn() => match ($stream->current()->type) {
			LegacyToken::HTML_TAG_END => null,
			default => $this->parser->parseInText(),
		}), $token->line);
		$stream->consume(LegacyToken::HTML_TAG_END);
		$this->parser->location = TemplateParser::LocationText;
		return $node;
	}


	private function createTagFromAttr(LegacyToken $token): Tag
	{
		return new Tag(
			name: preg_replace('~n:(inner-|tag-|)~', '', $token->name),
			args: $token->value,
			line: $token->line,
			prefix: match (true) {
				str_starts_with($token->name, 'n:inner-') => Tag::PrefixInner,
				str_starts_with($token->name, 'n:tag-') => Tag::PrefixTag,
				default => Tag::PrefixNone,
			},
			location: $this->parser->location,
			htmlElement: $this->element,
		);
	}


	private function isClosingTag(?LegacyToken $token, string $name): bool
	{
		return $token
			&& $token->is(LegacyToken::HTML_TAG_BEGIN)
			&& $token->closing
			&& strcasecmp($name, $token->name) === 0;
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

		$token = $this->parser->getStream()->current();
		if (!$this->isClosingTag($token, $elem->name)) {
			$this->parser->getStream()->throwUnexpectedException(
				addendum: ", expecting </{$elem->name}> for element started on line $elem->line",
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
