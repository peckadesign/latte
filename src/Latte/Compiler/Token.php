<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Strict;


final class Token
{
	use Strict;

	public const
		Text = 10000,
		Whitespace = 10002,
		Slash = 10003,
		Equals = 10004,
		Quote = 10005; // single or double quote

	public const
		Latte_TagOpen = 10010,
		Latte_TagClose = 10011,
		Latte_Name = 10012,
		Latte_Args = 10013,
		Latte_CommentOpen = 10014,
		Latte_CommentClose = 10015;

	public const
		Html_TagOpen = 10020,
		Html_TagClose = 10021,
		Html_CommentOpen = 10022,
		Html_CommentClose = 10023,
		Html_BogusOpen = 10024,
		Html_Name = 10025;

	public const NAMES = [
		self::Text => 'text',
		self::Whitespace => 'whitespace',
		self::Slash => "'/'",
		self::Equals => "'='",
		self::Quote => 'quote',

		self::Latte_TagOpen => "'{'",
		self::Latte_TagClose => "'}'",
		self::Latte_Name => 'tag name',
		self::Latte_Args => 'arguments',
		self::Latte_CommentOpen => "'{*'",
		self::Latte_CommentClose => "'*}'",

		self::Html_TagOpen => 'HTML tag',
		self::Html_TagClose => 'end of HTML tag',
		self::Html_CommentOpen => 'HTML comment',
		self::Html_CommentClose => 'end of HTML comment',
		self::Html_BogusOpen => 'HTML bogus tag',
		self::Html_Name => 'HTML name',
	];


	public function __construct(
		public int $type,
		public string $text,
		public ?int $line = null,
		public ?int $column = null,
	) {
	}


	public function is(int|string ...$kind): bool
	{
		return in_array($this->type, $kind, true)
			|| in_array($this->text, $kind, true);
	}
}
