<?php

declare(strict_types=1);

use Latte\Context;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);


$template = $latte->createTemplate('');
Assert::same(Context::Html, $template->getContentType());

$template = $latte->createTemplate('{contentType xml}');
Assert::same(Context::Xml, $template->getContentType());

Assert::exception(
	fn() => $latte->createTemplate('{block}{contentType xml}{/block}'),
	Latte\CompileException::class,
	'{contentType} is allowed only in template header.',
);

Assert::exception(
	fn() => $latte->createTemplate('<div>{contentType xml}</div>'),
	Latte\CompileException::class,
	'{contentType} is allowed only in template header.',
);

Assert::same(
	'<script> <p n:if=0 /> </script>',
	$latte->renderToString('{contentType html}<script> <p n:if=0 /> </script>'),
);

Assert::same(
	'<script>  </script>',
	$latte->renderToString('{contentType xml}<script> <p n:if=0 /> </script>'),
);

Assert::same(
	'<p n:if=0 />',
	$latte->renderToString('{contentType text}<p n:if=0 />'),
);

// defined on $latte
$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);
$latte->setContentType(Context::Xml);

$template = $latte->createTemplate('--');
Assert::same(Context::Xml, $template->getContentType());


// traversing
$latte->addExtension($extension = new DumpExtension);
$latte->compile('{contentType xml}');
Assert::match(<<<'XX'
	Template:
		Fragment:
			ContentType:
		Fragment:
	XX, $extension->export());
