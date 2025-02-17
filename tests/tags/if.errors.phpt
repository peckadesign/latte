<?php

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::exception(
	fn() => $latte->compile('{if 1}{else if a}{/if}'),
	Latte\CompileException::class,
	'Arguments are not allowed in {else}, did you mean {elseif}?',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{else a}{/if}'),
	Latte\CompileException::class,
	"Unexpected 'a', expecting end of tag in {else} (at column 13)",
);

Assert::exception(
	fn() => $latte->compile('{else}'),
	Latte\CompileException::class,
	'Unexpected tag {else}',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{else}{else}{/if}'),
	Latte\CompileException::class,
	'Unexpected tag {else}',
);

Assert::exception(
	fn() => $latte->compile('{elseif a}'),
	Latte\CompileException::class,
	'Unexpected tag {elseif}',
);

Assert::exception(
	fn() => $latte->compile('{if 1}{else}{elseif a}{/if}'),
	Latte\CompileException::class,
	'Unexpected tag {elseif}',
);

Assert::exception(
	fn() => $latte->compile('{if}{elseif a}{/if 1}'),
	Latte\CompileException::class,
	'Unexpected tag {elseif}',
);
