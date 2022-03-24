<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	<<<'XX'
		%A%echo LR\Filters::escapeHtmlText(test(function () {
					return 1;
				}))%A%
		XX,
	$latte->compile('{test(function () { return 1;})}'),
);

Assert::match(
	<<<'XX'
		%A%echo LR\Filters::escapeHtmlText(test(function () use ($a) {
					return 1;
				}))%A%
		XX,
	$latte->compile('{test(function () use ($a) { return 1;})}'),
);

Assert::match(
	'%A%echo LR\Filters::escapeHtmlText(test(fn () => 1))%A%',
	$latte->compile('{test(fn () => 1)}'),
);

Assert::match(
	"%A%('foo')/ **/('bar')%A%",
	$latte->compile('{(foo)//**/**/(bar)}'),
);

Assert::match(
	"%A%
		if (1) /* line 1 */ {
			echo 'xxx';
		}
%A%",
	$latte->compile('{if 1}xxx{/}'),
);

Assert::match( // fix #58
	'x',
	$latte->renderToString('{contentType application/xml}{if true}x{/if}'),
);

Assert::match(
	'<a href=""></a>',
	$latte->renderToString('<a href="{ifset $x}{$x}{/ifset}"></a>'),
);
