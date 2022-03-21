<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\Strict;


final class TokenStream
{
	use Strict;

	/** @var LegacyToken[] */
	private array $tokens;
	private int $index = 0;


	/**
	 * @param  LegacyToken[]  $tokens
	 */
	public function __construct(array $tokens)
	{
		$this->tokens = $tokens;
	}


	/**
	 * Gets the token at current position or null on end.
	 */
	public function current(): ?LegacyToken
	{
		return $this->tokens[$this->index] ?? null;
	}


	/**
	 * Tells whether the token at current position is of given kind.
	 */
	public function is(int|string ...$kind): bool
	{
		return $this->current()?->is(...$kind) ?? false;
	}


	/**
	 * Gets the token at $offset from the current position.
	 */
	public function peek(int $offset): ?LegacyToken
	{
		return $this->tokens[$this->index + $offset] ?? null;
	}


	/**
	 * Consumes the current token (if is of given kind) or throws exception on end.
	 * @throws CompileException
	 */
	public function consume(int|string ...$kind): LegacyToken
	{
		$token = $this->current();
		if (!$token || ($kind && !$token->is(...$kind))) {
			$this->throwUnexpectedException($kind);
		}
		$this->index++;
		return $token;
	}


	/**
	 * Consumes the current token of given kind or returns null.
	 */
	public function tryConsume(int|string ...$kind): ?LegacyToken
	{
		if (($token = $this->current())
			&& (!$kind || $token->is(...$kind))
		) {
			$this->index++;
			return $token;
		}
		return null;
	}


	/**
	 * Sets the input cursor to the position.
	 */
	public function seek(int $index): void
	{
		if ($index > count($this->tokens) || $index < 0) {
			throw new \InvalidArgumentException('The position is out of range.');
		}
		$this->index = $index;
	}


	/**
	 * Returns the cursor position.
	 */
	public function getIndex(): int
	{
		return $this->index;
	}


	/**
	 * @throws CompileException
	 * @return never
	 */
	public function throwUnexpectedException(array $expected = [], $addendum = ''): void
	{
		$s = null;
		$i = 0;
		do {
			if (!($token = $this->peek($i++))) {
				break;
			}
			$s .= $token->text;
			if (strlen($s) > 5) {
				break;
			}
		} while (true);

		$last = $this->current() ?? $this->peek(-1);
		throw new CompileException(
			'Unexpected '
			. ($s === null
				? 'end'
				: "'" . trim($s) . "'")
			. ($expected && count($expected) < 5
				? ', expecting ' . implode(', ', $expected)
				: '')
			. $addendum,
			$last->line ?? null,
		);
	}
}
