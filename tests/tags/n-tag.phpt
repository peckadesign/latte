<?php

/**
 * n:tag
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

Assert::match(
	'<span>Content</span>',
	$latte->renderToString('<div n:tag="span">Content</div>'),
);


Assert::match(
	'<span></span>',
	$latte->renderToString('<DIV n:tag="span"></DIV>'),
);


Assert::match(
	<<<'XX'
			<h1
		class="a" title="b">
				<h2></h2>
			</h1>
		XX,
	$latte->renderToString(
		<<<'XX'
				<div
			class="a" n:tag="h1" title="b">
					<div n:tag="h2"></div
				>
				</div>
			XX,
	),
);


Assert::match(
	'<br class="a" title="b">',
	$latte->renderToString('<img class="a" n:tag="br" title="b">'),
);


Assert::match(
	'<br/>',
	$latte->renderToString('<img n:tag="br"/>'),
);


// no change
Assert::match(
	'<img/>',
	$latte->renderToString('<img n:tag="null"/>'),
);


Assert::match(
	<<<'XX'
		%A%
				$ʟ_tag[0] = '';
				echo '<';
				echo $ʟ_tmp = (Latte\Essential\Tags\NTagAttribute::check('div', 'h' . 1));
				$ʟ_tag[0] = '</' . $ʟ_tmp . '>' . $ʟ_tag[0];
				echo ' class="bar" ';
				if (isset($id)) /* line 1 */ {
					echo 'id="content"';
				}
				echo '>';
				echo $ʟ_tag[0];
		%A%
		XX,
	$latte->compile('<div class="bar" {ifset $id}id="content"{/ifset} n:tag="h . 1"></div>'),
);


Assert::exception(
	fn() => $latte->compile('<div n:tag/>'),
	Latte\CompileException::class,
	'Missing arguments in n:tag',
);


Assert::exception(
	fn() => $latte->compile('<div n:inner-tag/>'),
	Latte\CompileException::class,
	'Unexpected attribute n:inner-tag, did you mean n:inner-try?',
);


Assert::exception(
	fn() => $latte->renderToString('<div n:tag="1"></div>'),
	Latte\RuntimeException::class,
	'Invalid tag name 1',
);


Assert::exception(
	fn() => $latte->compile('<script n:tag="foo"></script>'),
	Latte\CompileException::class,
	'Attribute n:tag is not allowed in <script> or <style>',
);


Assert::exception(
	fn() => $latte->compile('<STYLE n:tag="foo"></STYLE>'),
	Latte\CompileException::class,
	'Attribute n:tag is not allowed in <script> or <style>',
);


Assert::exception(
	fn() => $latte->renderToString('<div n:tag="\'SCRIPT\'"></div>'),
	Latte\RuntimeException::class,
	'Forbidden tag <div> change to <SCRIPT>.',
);


Assert::exception(
	fn() => $latte->renderToString('<div n:tag="style"></div>'),
	Latte\RuntimeException::class,
	'Forbidden tag <div> change to <style>.',
);


Assert::exception(
	fn() => $latte->renderToString('<span n:tag="br"></span>'),
	Latte\RuntimeException::class,
	'Forbidden tag <span> change to <br>.',
);


// traversing
Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Element:
				name: input
				Auxiliary:
				StaticCall:
					Name:
						parts: ['Latte', 'Essential', 'Tags', 'NTagAttribute']
					Identifier:
						name: check
					Arg:
						String:
							value: input
					Arg:
						Variable:
							name: var
				Fragment:
	XX, exportTraversing('<input n:tag="$var">'));
