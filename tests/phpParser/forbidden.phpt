<?php

// Forbidden syntax

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


// operator | is used for filters
Assert::exception(
	fn() => parseCode('$a | $b'),
	Latte\CompileException::class,
	"Unexpected '| \$b' (at column 4)",
);

// function declaration
Assert::exception(
	fn() => parseCode('function getArr() {	return [4, 5]; }'),
	Latte\CompileException::class,
	"Unexpected 'getArr', expecting '(' (at column 10)",
);

// missing return
Assert::exception(
	fn() => parseCode('function($a) { $a; }'),
	Latte\CompileException::class,
	"Unexpected '\$a', expecting 'return' or '}' (at column 16)",
);

// static closure
Assert::exception(
	fn() => parseCode('static function() { return 0; }'),
	Latte\CompileException::class,
	"Unexpected 'function' (at column 8)",
);

// variable variable
Assert::exception(
	fn() => parseCode('$$a'),
	Latte\CompileException::class,
	"Unexpected '\$a', expecting '{' (at column 2)",
);

// forbidden keyword
Assert::exception(
	fn() => parseCode('include "A.php"'),
	Latte\CompileException::class,
	"Unexpected '\"A.php\"'",
);

// shell execution
Assert::exception(
	fn() => parseCode('`test`'),
	Latte\CompileException::class,
	"Unexpected '`' (at column 1)",
);

// throwing
Assert::exception(
	fn() => parseCode('throw new Exception'),
	Latte\CompileException::class,
	"Keyword 'throw' is forbidden in Latte (at column 1)",
);

// syntax error, not number
Assert::exception(
	fn() => parseCode('100_'),
	Latte\CompileException::class,
	"Unexpected '_' (at column 4)",
);

// syntax error, not number
Assert::exception(
	fn() => parseCode('1__1'),
	Latte\CompileException::class,
	"Unexpected '__1' (at column 2)",
);

// syntax error, not unquoted string
Assert::exception(
	fn() => parseCode('a---b--c'),
	Latte\CompileException::class,
	"Unexpected '---b--c' (at column 2)",
);

// syntax error, not unquoted string
Assert::exception(
	fn() => parseCode('--ab'),
	Latte\CompileException::class,
	"Unexpected end, expecting '[' or '->' or '?->' or '??->' (at column 3)",
);

// invalid octal
Assert::exception(
	fn() => parseCode('0787'),
	Latte\CompileException::class,
	'Invalid numeric literal',
);

// "comments"
Assert::exception(
	fn() => parseCode('#comment'),
	Latte\CompileException::class,
	"Unexpected '#comment' (at column 1)",
);

// "comments"
Assert::exception(
	fn() => parseCode('//comment'),
	Latte\CompileException::class,
	"Unexpected '//comment' (at column 1)",
);

// { } access
Assert::exception(
	fn() => parseCode('$a{"b"}'),
	Latte\CompileException::class,
	"Unexpected '{\"b\"}' (at column 3)",
);

// ${...} is not supported
Assert::exception(
	fn() => parseCode('"a${b}c"'),
	Latte\CompileException::class,
	'Syntax ${...} is not supported (at column 3)',
);

// b"" is not supported
Assert::exception(
	fn() => parseCode('b""'),
	Latte\CompileException::class,
	"Unexpected '\"\"'",
);

// invalid octal number
Assert::exception(
	fn() => parseCode('01777777777787'),
	Latte\CompileException::class,
	'Invalid numeric literal',
);

// casts to boolean, double, real, unset
Assert::exception(
	fn() => parseCode('(boolean) $a'),
	Latte\CompileException::class,
	"Unexpected '\$a' (at column 11)",
);
