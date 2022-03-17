<?php

declare(strict_types=1);

use Latte\Compiler\PrintContext;
use Latte\Compiler\TagLexer;
use Latte\Compiler\TagParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function format(string $str)
{
	$tokens = (new TagLexer)->tokenize($str);
	$parser = new TagParser($tokens);
	$node = $parser->parseUnquotedStringOrExpression();
	if (!$parser->isEnd()) {
		throw $parser->stream->buildUnexpectedException();
	}
	return $node->print(new PrintContext);
}


Assert::exception(
	fn() => format(''),
	Latte\CompileException::class,
	'Unexpected end',
);

Assert::same('0', format('0'));
Assert::same('-0.0', format('-0.0'));
Assert::same("'symbol'", format('symbol'));
Assert::same('$var', format('$var'));
Assert::same('"symbol{$var}"', format('symbol$var'));
Assert::same("'var'", format("'var'"));
Assert::same("'var'", format('"var"'));
Assert::same("'v\"ar'", format('"v\\"ar"'));
Assert::same("'var' . 'var'", format("var.'var'"));
Assert::same("\$var['var']", format('$var[var]'));
Assert::same("\$x['[x]']", format('$x["[x]"]'));
Assert::same('"item-{$x}"', format('item-$x'));
Assert::same('"item-{{$x}()}"', format('item-{$x()}')); // bc break
Assert::same("'null'", format('null')); // bc breaks
Assert::same("'NULL'", format('NULL'));
Assert::same("'true'", format('true'));
Assert::same("'TRUE'", format('TRUE'));
Assert::same("'false'", format('false'));
Assert::same("'FALSE'", format('FALSE'));
Assert::same("'Null'", format('Null'));
Assert::same("'True'", format('True'));
Assert::same("'False'", format('False'));
Assert::same('Foo::CONST', format('Foo::CONST'));
Assert::same('\Namespace0\Class_1::CONST_X', format('\Namespace0\Class_1::CONST_X'));
Assert::same("'symbol'", format('(symbol)'));
Assert::same('M_PI', format('(M_PI)'));
Assert::same('$expr', format('($expr)'));
Assert::same('$expr ? 1 + 2 : [3, 4]', format('($expr ? (1+2) : [3,4])'));
Assert::same('$expr ? 1 + 2 : [3, 4]', format('$expr ? (1+2) : [3,4]'));
Assert::same('fnc() ? 1 + 2 : [3, 4]', format('fnc() ? (1+2) : [3,4]'));

Assert::exception(
	fn() => format("'var\""),
	Latte\CompileException::class,
	'Unterminated string (at column 1)',
);
