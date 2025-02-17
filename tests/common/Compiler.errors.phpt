<?php

/**
 * Test: Compile errors.
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('Block{/block}'),
	Latte\CompileException::class,
	"Unexpected '{/block' (at column 6)",
);

Assert::exception(
	fn() => $latte->compile('<a {if}n:href>'),
	Latte\CompileException::class,
	'Attribute n:href must not appear inside {tags} (at column 8)',
);

Assert::exception(
	fn() => $latte->compile('<a n:href n:href>'),
	Latte\CompileException::class,
	'Found multiple attributes n:href (at column 11)',
);

Assert::match(
	'<div c=comment -->',
	$latte->renderToString('<div c=comment {="--"}>'),
);

Assert::exception(
	fn() => $latte->compile('<a n:class class>'),
	Latte\CompileException::class,
	'It is not possible to combine class with n:class.',
);

Assert::exception(
	fn() => $latte->compile('<p title=""</p>'),
	'Latte\CompileException',
	'Unexpected </p> (at column 12)',
);

Assert::exception(
	fn() => $latte->compile('<p title=>'),
	'Latte\CompileException',
	"Unexpected '>' (at column 10)",
);


Assert::exception(
	fn() => $latte->compile("<span {='title'}={=''}></span>"),
	Latte\CompileException::class,
	"Unexpected '={=''}', expecting end of HTML tag (at column 17)",
);


Assert::exception(
	fn() => $latte->compile('{time() /}'),
	Latte\CompileException::class,
	'Unexpected /} in tag {=time() /}',
);


// brackets balaning
Assert::exception(
	fn() => $latte->compile('{=)}'),
	Latte\CompileException::class,
	"Unexpected ')' (at column 3)",
);

Assert::exception(
	fn() => $latte->compile('{=[(])}'),
	Latte\CompileException::class,
	"Unexpected ']' (at column 5)",
);


// forbidden keywords
Assert::exception(
	fn() => $latte->compile('{php function test() }'),
	Latte\CompileException::class,
	"Unexpected 'test', expecting '(' (at column 15)",
);

Assert::exception(
	fn() => $latte->compile('{php class test }'),
	Latte\CompileException::class,
	"Unexpected 'test', expecting end of tag in {php} (at column 12)",
);

Assert::exception(
	fn() => $latte->compile('{php return}'),
	Latte\CompileException::class,
	"Unexpected 'return' (at column 6)",
);

Assert::exception(
	fn() => $latte->compile('{php yield $x}'),
	Latte\CompileException::class,
	"Keyword 'yield' is forbidden in Latte (at column 6)",
);

Assert::exception(
	fn() => $latte->compile('{=`whoami`}'),
	Latte\CompileException::class,
	"Unexpected '`' (at column 3)",
);


// forbidden variables
Assert::exception(function () use ($latte) {
	$latte->compile('{$ʟ_tmp}');
}, Latte\CompileException::class, 'Forbidden variable $ʟ_tmp.');


// unclosed macros
Assert::exception(
	fn() => $latte->compile('{if 1}'),
	Latte\CompileException::class,
	'Unexpected end, expecting {/if}',
);

Assert::exception(
	fn() => $latte->compile('<p n:if=1><span n:if=1>'),
	Latte\CompileException::class,
	'Unexpected end, expecting </span> for element started on line 1 (at column 23)',
);

Assert::exception(
	fn() => $latte->compile('<p n:if=1><span n:if=1></i>'),
	Latte\CompileException::class,
	"Unexpected '</i>', expecting </span> for element started on line 1 (at column 24)",
);

Assert::exception(
	fn() => $latte->compile('{/if}'),
	Latte\CompileException::class,
	"Unexpected '{/if}' (at column 1)",
);

Assert::exception(
	fn() => $latte->compile('{if 1}{/foreach}'),
	Latte\CompileException::class,
	'Unexpected {/foreach}, expecting {/if}',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{/if 2}'),
	Latte\CompileException::class,
	"Unexpected '2', expecting end of tag in {/if} (at column 12)",
);

Assert::exception(
	fn() => $latte->compile('<span n:if=1>{foreach $a as $b}</span>'),
	Latte\CompileException::class,
	'Unexpected end, expecting {/foreach}',
);

Assert::exception(
	fn() => $latte->compile('<span n:if=1>{/if}'),
	Latte\CompileException::class,
	"Unexpected '{/if}', expecting </span> for element started on line 1 (at column 14)",
);

Assert::exception(
	fn() => $latte->compile(<<<'XX'
				{foreach [] as $item}
					<li><a n:tag-if="$iterator->odd"></li>
				{/foreach}
		XX),
	Latte\CompileException::class,
	"Unexpected '</li>', expecting </a> for element started on line 2 (on line 2 at column 37)",
);
