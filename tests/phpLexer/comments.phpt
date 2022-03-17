<?php

// scalars

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/** rusko=zlo :
	  * a, b
	  */

	// single line
	$c = true; // ffo
	#$c;
	XX;

$tokens = tokenize($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportTokens($tokens),
);

__halt_compiler();
#1:1   Php_Comment     '/** rusko=zlo :\n  * a, b\n  */'
#3:5   Php_Whitespace  '\n\n'
#5:1   '/'
#5:2   '/'
#5:3   Php_Whitespace  ' '
#5:4   Php_Identifier  'single'
#5:10  Php_Whitespace  ' '
#5:11  Php_Identifier  'line'
#5:15  Php_Whitespace  '\n'
#6:1   Php_Variable    '$c'
#6:3   Php_Whitespace  ' '
#6:4   '='
#6:5   Php_Whitespace  ' '
#6:6   Php_ConstantString 'true'
#6:10  ';'
#6:11  Php_Whitespace  ' '
#6:12  '/'
#6:13  '/'
#6:14  Php_Whitespace  ' '
#6:15  Php_Identifier  'ffo'
#6:18  Php_Whitespace  '\n'
#7:1   '#'
#7:2   Php_Variable    '$c'
#7:4   ';'
