<?php

/**
 * n:attr
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	<p n:attr="title => hello, lang => isset($lang) ? $lang"> </p>

	<p n:attr="[title => hello]"> </p>

	EOD;

Assert::match(
	<<<'XX'
		%A%
				echo '
		<p';
				$ʟ_tmp = ['title' => 'hello', 'lang' => isset($lang) ? $lang : null];
				echo Latte\Essential\Tags\NAttrAttribute::print(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) /* line 2 */;
				echo '> </p>

		<p';
				$ʟ_tmp = [['title' => 'hello']];
				echo Latte\Essential\Tags\NAttrAttribute::print(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) /* line 4 */;
				echo '> </p>
		';
		%A%
		XX,
	$latte->compile($template),
);

Assert::match(
	<<<'XX'

		<p title="hello"> </p>

		<p title="hello"> </p>
		XX,
	$latte->renderToString($template),
);


Assert::exception(
	fn() => $latte->compile('<div n:attr/>'),
	Latte\CompileException::class,
	'Missing arguments in n:attr',
);


Assert::exception(
	fn() => $latte->compile('<div n:inner-attr/>'),
	Latte\CompileException::class,
	'Unexpected attribute n:inner-attr, did you mean n:inner-try?',
);


// traversing
Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Element:
				name: input
				Auxiliary:
				Fragment:
					Auxiliary:
	XX, exportTraversing('<input n:attr="title => hello">'));
