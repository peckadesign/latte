<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Context;
use Latte\RegexpException;
use Latte\Strict;


final class TemplateLexer
{
	use Strict;

	/** regular expression for single & double quoted PHP string */
	public const ReString = '\'(?:\\\\.|[^\'\\\\])*+\'|"(?:\\\\.|[^"\\\\])*+"';

	/** HTML tag name for Latte needs (actually is [a-zA-Z][^\s/>]*) */
	public const ReTagName = '[a-zA-Z][a-zA-Z0-9:_.-]*';

	/** special HTML attribute prefix */
	public const NPrefix = 'n:';
	private const ReValueName = '[^\p{C} "\'<>=`/{}]+';
	private const ReIndent = '((?<=\n|^)[ \t]+)?';
	private const StateEnd = 'end';

	/** @var array<string, array{string, string}> */
	public array $syntaxes = [
		'latte' => ['\{(?![\s\'"{}])', '\}'], // {...}
		'double' => ['\{\{(?![\s\'"{}])', '\}\}'], // {{...}}
		'off' => ['\{(?=/syntax\})', '\}'], // {/syntax}
	];

	/** @var string[] */
	private array $delimiters;
	private TagLexer $tagLexer;
	private string $input;
	private int $offset;
	private int $line;
	private int $column;
	private array $states;
	private bool $xmlMode;


	/** @return \Generator<Token> */
	public function tokenize(string $template, string $contentType = Context::Html): \Generator
	{
		$this->input = $this->normalize($template);
		$this->offset = 0;
		$this->line = $this->column = 1;
		$this->states = [];
		$this->setContentType($contentType);
		$this->setSyntax(null);
		$this->tagLexer = new TagLexer;

		do {
			$state = $this->states[0];
			yield from $this->{$state['name']}(...$state['args']);
		} while ($this->states[0]['name'] !== self::StateEnd);

		if ($this->offset < strlen($this->input)) {
			throw new CompileException('Unexpected ' . substr($this->input, $this->offset, 10), $this->line, $this->column);
		}
	}


	private function statePlain(): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??
			(
				(?<Latte_TagOpen>' . self::ReIndent . $this->delimiters[0] . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . self::ReIndent . $this->delimiters[0] . '\*)|      # {* comment
				$
			)
		~xsiAuD');

		if (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateLatteTag(): \Generator
	{
		$this->popState();
		$m = yield from $this->match('~
			(?<Slash>/)?
			(?<Latte_Name>=|_(?!_)|[a-z]\w*+(?:[.:-]\w+)*+(?!::|\(|\\\\))?   # name, /name, but not function( or class:: or namespace\
		~xsiAu');

		if (!$m) {
			throw new CompileException('Malformed tag contents.', $this->line);
		}

		$m = yield from $this->match('~
			(?<Slash>/)?
			(?<Latte_TagClose>' . $this->delimiters[1] . '([ \t]*\n)?)
		~xsiAu');

		if (!$m) {
			$tokens = $this->tagLexer->tokenize($this->input, $this->line, $this->column, $this->offset, toEnd: false);
			if ($tokens && end($tokens)->is('/')) {
				end($tokens)->type = Token::Slash;
			}
			yield from $tokens;

			$m = yield from $this->match('~
				(?<Latte_TagClose>' . $this->delimiters[1] . '([ \t]*\n)?)
			~xsiAu');
		}

		if (!$m) {
			throw new CompileException('Malformed tag contents.', $this->line);
		}
	}


	private function stateLatteComment(): \Generator
	{
		$this->popState();
		$m = yield from $this->match('~
			(?<Text>.+?)??
			(?<Latte_CommentClose>\*' . $this->delimiters[1] . '([ \t]*\n{1,2})?)
		~xsiAu');

		if (!$m) {
			throw new CompileException('Malformed comment contents.', $this->line);
		}
	}


	private function stateHtmlText(): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??
			(
				(?<Html_TagOpen>' . self::ReIndent . '<)(?<Slash>/)?(?<Html_Name>' . self::ReTagName . ')|  # <tag </tag
				(?<Html_CommentOpen><!--(?!>|->))|                                              # <!-- comment
				(?<Html_BogusOpen><[/?!])|                                                      # <!doctype <?xml or error
				(?<Latte_TagOpen>' . self::ReIndent . $this->delimiters[0] . '(?!\*))|          # {tag
				(?<Latte_CommentOpen>' . self::ReIndent . $this->delimiters[0] . '\*)|          # {* comment
				$
			)
		~xsiAuD');

		if (isset($m['Html_TagOpen'])) {
			$tagName = isset($m['Slash']) ? null : strtolower($m['Html_Name']);
			$this->setState('stateHtmlTag', $tagName);
		} elseif (isset($m['Html_CommentOpen'])) {
			$this->setState('stateHtmlComment');
		} elseif (isset($m['Html_BogusOpen'])) {
			$this->setState('stateHtmlBogus');
		} elseif (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlTag(?string $tagName = null, ?string $attrName = null): \Generator
	{
		$m = yield from $this->match('~
			(?<Html_Name>' . self::ReValueName . ')|                   # HTML attribute name/value
			(?<Whitespace>\s+)|                                        # whitespace
			(?<Equals>=)|
			(?<Quote>["\'])|
			(?<Slash>/)?(?<Html_TagClose>>([ \t]*\n)?)|                # > />
			(?<Latte_TagOpen>' . $this->delimiters[0] . '(?!\*))|      # {tag
			(?<Latte_CommentOpen>' . $this->delimiters[0] . '\*)       # {* comment
		~xsiAu');

		if (isset($m['Html_Name'])) {
			$this->states[0]['args'][1] = $m['Html_Name'];
		} elseif (isset($m['Whitespace']) || isset($m['Equals'])) {
		} elseif (isset($m['Quote'])) {
			$this->pushState(str_starts_with($attrName, self::NPrefix)
				? 'stateHtmlQuotedNAttrValue'
				: 'stateHtmlQuotedValue', $m['Quote']);
		} elseif (
			isset($m['Html_TagClose'])
			&& !$this->xmlMode
			&& !isset($m['Slash'])
			&& in_array($tagName, ['script', 'style'], true)
		) {
			$this->setState('stateHtmlRCData', $tagName);
		} elseif (isset($m['Html_TagClose'])) {
			$this->setState('stateHtmlText');
		} elseif (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlQuotedValue(string $quote): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??(
				(?<Quote>' . $quote . ')|
				(?<Latte_TagOpen>' . $this->delimiters[0] . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . $this->delimiters[0] . '\*)       # {* comment
			)
		~xsiAu');

		if ($m['Quote']) {
			$this->popState();
		} elseif (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlQuotedNAttrValue(string $quote): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??(?<Quote>' . $quote . ')|
		~xsiAu');

		if ($m['Quote']) {
			$this->popState();
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlRCData(string $tagName): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??
			(
				(?<Html_TagOpen>' . self::ReIndent . '<)(?<Slash>/)(?<Html_Name>' . preg_quote($tagName, '~') . ')| # </tag
				(?<Latte_TagOpen>' . self::ReIndent . $this->delimiters[0] . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . self::ReIndent . $this->delimiters[0] . '\*)|      # {* comment
				$
			)
		~xsiAu');

		if (isset($m['Html_TagOpen'])) {
			$this->setState('stateHtmlTag');
		} elseif (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlComment(): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??
			(
				(?<Html_CommentClose>-->)|                                                  # -->
				(?<Latte_TagOpen>' . self::ReIndent . $this->delimiters[0] . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . self::ReIndent . $this->delimiters[0] . '\*)       # {* comment
			)
		~xsiAu');

		if (isset($m['Html_CommentClose'])) {
			$this->setState('stateHtmlText');
		} elseif (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	private function stateHtmlBogus(): \Generator
	{
		$m = yield from $this->match('~
			(?<Text>.+?)??(
				(?<Html_TagClose>>)|                                       # >
				(?<Latte_TagOpen>' . $this->delimiters[0] . '(?!\*))|      # {tag
				(?<Latte_CommentOpen>' . $this->delimiters[0] . '\*)       # {* comment
			)
		~xsiAu');

		if (isset($m['Html_TagClose'])) {
			$this->setState('stateHtmlText');
		} elseif (isset($m['Latte_TagOpen'])) {
			$this->pushState('stateLatteTag');
		} elseif (isset($m['Latte_CommentOpen'])) {
			$this->pushState('stateLatteComment');
		} else {
			$this->setState(self::StateEnd);
		}
	}


	/**
	 * Matches next token.
	 */
	private function match(string $re): \Generator
	{
		if (!preg_match($re, $this->input, $matches, PREG_UNMATCHED_AS_NULL, $this->offset)) {
			if (preg_last_error()) {
				throw new RegexpException;
			}

			return [];
		}

		foreach ($matches as $k => $v) {
			if ($v !== null && !\is_int($k)) {
				yield new Token(\constant(Token::class . '::' . $k), $v, $this->line, $this->column);

				if ($lines = substr_count($v, "\n")) {
					$this->line += $lines;
					$this->column = strlen($v) - strrpos($v, "\n");
				} else {
					$this->column += strlen($v);
				}
			}
		}

		$this->offset += strlen($matches[0]);
		return $matches;
	}


	public function setContentType(string $type): static
	{
		if ($type === Context::Html || $type === Context::Xml) {
			$this->setState('stateHtmlText');
			$this->xmlMode = $type === Context::Xml;
		} else {
			$this->setState('statePlain');
		}

		return $this;
	}


	private function setState(string $state, ...$args): void
	{
		$this->states[0] = ['name' => $state, 'args' => $args];
	}


	private function pushState(string $state, ...$args): void
	{
		array_unshift($this->states, null);
		$this->setState($state, ...$args);
	}


	private function popState(): void
	{
		array_shift($this->states);
	}


	/**
	 * Changes macro tag delimiters.
	 */
	public function setSyntax(?string $type): static
	{
		$type ??= 'latte';
		if (!isset($this->syntaxes[$type])) {
			throw new \InvalidArgumentException("Unknown syntax '$type'");
		}

		$this->setDelimiters($this->syntaxes[$type][0], $this->syntaxes[$type][1]);
		return $this;
	}


	/**
	 * Changes macro tag delimiters (as regular expression).
	 */
	public function setDelimiters(string $left, string $right): static
	{
		$this->delimiters = [$left, $right];
		return $this;
	}


	private function normalize(string $str): string
	{
		if (str_starts_with($str, "\u{FEFF}")) { // BOM
			$str = substr($str, 3);
		}

		$str = str_replace("\r\n", "\n", $str);

		if (!preg_match('##u', $str)) {
			preg_match('#(?:[\x00-\x7F]|[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3})*+#A', $str, $m);
			$line = substr_count($m[0], "\n") + 1;
			$column = strlen($m[0]) - strrpos($m[0], "\n");
			throw new CompileException('Template is not valid UTF-8 stream.', $line, $column);

		} elseif (preg_match('#[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]#', $str, $m, PREG_OFFSET_CAPTURE)) {
			$line = substr_count($str, "\n", 0, $m[0][1]) + 1;
			$column = $m[0][1] - strrpos($str, "\n", -$m[0][1]);
			throw new CompileException('Template contains control character \x' . dechex(ord($m[0][0])), $line, $column);
		}
		return $str;
	}
}
