<?php

declare(strict_types=1);

use Latte\Essential\Tags\NAttrAttribute;
use Latte\Runtime\Filters;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


Assert::same('', NAttrAttribute::print(null));

Assert::same(' style="float:left" class="three" a=\'<>"\' b="\'" title="0" checked', NAttrAttribute::print([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => true,
	'selected' => false,
]));

Assert::same(' a="`test "', NAttrAttribute::print(['a' => '`test'])); // mXSS

Filters::$xml = true;
Assert::same(' style="float:left" class="three" a=\'&lt;>"\' b="\'" title="0" checked="checked"', NAttrAttribute::print([
	'style' => 'float:left',
	'class' => 'three',
	'a' => '<>"',
	'b' => "'",
	'title' => '0',
	'checked' => true,
	'selected' => false,
]));

// invalid UTF-8
Assert::same(" a=\"foo \u{D800} bar\"", NAttrAttribute::print(['a' => "foo \u{D800} bar"])); // invalid codepoint high surrogates
Assert::same(" a='foo \xE3\x80\x22 bar'", NAttrAttribute::print(['a' => "foo \xE3\x80\x22 bar"])); // stripped UTF
