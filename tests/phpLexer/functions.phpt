<?php

// different function constructs

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	require("sumfile.php");

	function myFunction($a) use (?$b)
	{
	   return 1;
	}

	fn() => 123;

	list($value1,$value2) = $c;
	if(empty($value1) && !isset($value1)) {
	  myFunction();
	}
	XX;

$tokens = tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Identifier  'require'
#1:8   '('
#1:    Php_ConstantEncapsedString '\"sumfile.php\"'
#1:21  ')'
#1:22  ';'
#1:23  Php_Whitespace  '\n\n'
#3:1   Php_Function    'function'
#3:9   Php_Whitespace  ' '
#3:10  Php_Identifier  'myFunction'
#3:20  '('
#3:21  Php_Variable    '$a'
#3:23  ')'
#3:24  Php_Whitespace  ' '
#3:25  Php_Use         'use'
#3:28  Php_Whitespace  ' '
#3:29  '('
#3:30  '?'
#3:31  Php_Variable    '$b'
#3:33  ')'
#3:34  Php_Whitespace  '\n'
#4:1   '{'
#4:2   Php_Whitespace  '\n   '
#5:4   Php_Return      'return'
#5:10  Php_Whitespace  ' '
#5:11  Php_LNumber     '1'
#5:12  ';'
#5:13  Php_Whitespace  '\n'
#6:1   '}'
#6:2   Php_Whitespace  '\n\n'
#8:1   Php_Fn          'fn'
#8:3   '('
#8:4   ')'
#8:5   Php_Whitespace  ' '
#8:6   Php_DoubleArrow '=>'
#8:8   Php_Whitespace  ' '
#8:9   Php_LNumber     '123'
#8:12  ';'
#8:13  Php_Whitespace  '\n\n'
#10:1  Php_List        'list'
#10:5  '('
#10:6  Php_Variable    '$value1'
#10:13 ','
#10:14 Php_Variable    '$value2'
#10:21 ')'
#10:22 Php_Whitespace  ' '
#10:23 '='
#10:24 Php_Whitespace  ' '
#10:25 Php_Variable    '$c'
#10:27 ';'
#10:28 Php_Whitespace  '\n'
#11:1  Php_Identifier  'if'
#11:3  '('
#11:4  Php_Empty       'empty'
#11:9  '('
#11:10 Php_Variable    '$value1'
#11:17 ')'
#11:18 Php_Whitespace  ' '
#11:19 Php_BooleanAnd  '&&'
#11:21 Php_Whitespace  ' '
#11:22 '!'
#11:23 Php_Isset       'isset'
#11:28 '('
#11:29 Php_Variable    '$value1'
#11:36 ')'
#11:37 ')'
#11:38 Php_Whitespace  ' '
#11:39 '{'
#11:40 Php_Whitespace  '\n  '
#12:3  Php_Identifier  'myFunction'
#12:13 '('
#12:14 ')'
#12:15 ';'
#12:16 Php_Whitespace  '\n'
#13:1  '}'
