<?php

declare(strict_types=1);

use Latte\Compiler\TemplateLexer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('', function () {
	$lexer = new TemplateLexer;
	iterator_to_array($lexer->tokenize("\n{a}"));
	iterator_to_array($lexer->tokenize(''));
});

$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("\xA0\xA0"), false),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream.',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("žluťoučký\n\xA0\xA0"), false),
	Latte\CompileException::class,
	'Template is not valid UTF-8 stream (on line 2 at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("{var \n'abc}"), false),
	Latte\CompileException::class,
	'Unterminated string (on line 2 at column 1)',
);


$lexer = new TemplateLexer;
Assert::exception(function () use (&$lexer) {
	return iterator_to_array($lexer->tokenize("\n{* \n'abc}"), false);
}, Latte\CompileException::class, 'Malformed comment contents (on line 2)');


$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize('{'), false),
	Latte\CompileException::class,
	'Malformed tag contents.',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("\n{"), false),
	Latte\CompileException::class,
	'Malformed tag contents (on line 2)',
);


$lexer = new TemplateLexer;
Assert::exception(
	fn() => iterator_to_array($lexer->tokenize("a\x00\x1F\x7Fb"), false),
	Latte\CompileException::class,
	'Template contains control character \x0 (at column 1)',
);
