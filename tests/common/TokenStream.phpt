<?php

declare(strict_types=1);

use Latte\CompileException;
use Latte\Compiler\LegacyToken;
use Latte\Compiler\TokenStream;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('empty', function () {
	$stream = new TokenStream([]);
	Assert::null($stream->current());
	Assert::false($stream->is());
	Assert::null($stream->peek(0));
	Assert::null($stream->tryConsume());
	Assert::same(0, $stream->getIndex());
});


test('current()', function () {
	$token = new LegacyToken;
	$stream = new TokenStream([$token]);

	Assert::same($token, $stream->current());
	Assert::same($token, $stream->tryConsume());
	Assert::null($stream->current());
});


test('is()', function () {
	$token = new LegacyToken;
	$token->text = 'foo';
	$token->type = LegacyToken::TEXT;
	$stream = new TokenStream([$token]);

	Assert::false($stream->is());
	Assert::true($stream->is('foo'));
	Assert::false($stream->is(''));
	Assert::true($stream->is('', 'foo'));
	Assert::true($stream->is(LegacyToken::TEXT));

	Assert::same($token, $stream->tryConsume());
	Assert::false($stream->is('foo'));
});


test('peek()', function () {
	$token1 = new LegacyToken;
	$token2 = new LegacyToken;
	$stream = new TokenStream([$token1, $token2]);

	Assert::null($stream->peek(-1));
	Assert::same(0, $stream->getIndex());
	Assert::same($token1, $stream->peek(0));
	Assert::same($token2, $stream->peek(1));
	Assert::null($stream->peek(2));
	Assert::same(0, $stream->getIndex());

	$stream->consume();
	Assert::null($stream->peek(-2));
	Assert::same($token1, $stream->peek(-1));
	Assert::same($token2, $stream->peek(0));
	Assert::null($stream->peek(1));

	$stream->consume();
	Assert::null($stream->peek(-3));
	Assert::same($token1, $stream->peek(-2));
	Assert::same($token2, $stream->peek(-1));
	Assert::null($stream->peek(0));
});


test('peek() jump forward', function () {
	$token1 = new LegacyToken;
	$token2 = new LegacyToken;
	$token3 = new LegacyToken;
	$stream = new TokenStream([$token1, $token2, $token3]);

	Assert::same($token3, $stream->peek(2));
});


test('consume() any token', function () {
	$token = new LegacyToken;
	$token->line = 123;
	$stream = new TokenStream([$token]);

	Assert::same($token, $stream->consume());
	Assert::same(1, $stream->getIndex());
	Assert::exception(
		fn() => $stream->consume(),
		CompileException::class,
		'Unexpected end (on line 123)',
	);
	Assert::same(1, $stream->getIndex());
});


test('consume() kind of token', function () {
	$token = new LegacyToken;
	$token->text = 'foo';
	$token->type = LegacyToken::TEXT;
	$stream = new TokenStream([$token]);

	Assert::exception(
		fn() => $stream->consume('bar'),
		CompileException::class,
		"Unexpected 'foo', expecting 'bar'",
	);
	Assert::same(0, $stream->getIndex());
	Assert::same($token, $stream->consume('foo'));
	Assert::same(1, $stream->getIndex());
});


test('tryConsume() any token', function () {
	$token = new LegacyToken;
	$stream = new TokenStream([$token]);

	Assert::same($token, $stream->tryConsume());
	Assert::same(1, $stream->getIndex());
	Assert::null($stream->tryConsume());
	Assert::same(1, $stream->getIndex());
});


test('tryConsume() kind of token', function () {
	$token = new LegacyToken;
	$token->text = 'foo';
	$token->type = LegacyToken::TEXT;
	$stream = new TokenStream([$token]);

	Assert::null($stream->tryConsume('bar'));
	Assert::same(0, $stream->getIndex());
	Assert::same($token, $stream->tryConsume('foo'));
	Assert::same(1, $stream->getIndex());
});


test('seek()', function () {
	$token = new LegacyToken;
	$stream = new TokenStream([$token]);

	Assert::noError(fn() => $stream->seek(0));
	Assert::noError(fn() => $stream->seek(1));
	Assert::exception(
		fn() => $stream->seek(2),
		InvalidArgumentException::class,
		'The position is out of range.',
	);
	Assert::exception(
		fn() => $stream->seek(-1),
		InvalidArgumentException::class,
		'The position is out of range.',
	);
});
