<?php

/**
 * Test: {for}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = <<<'EOD'

	{for $i = 0; $i < 10; $i++}
		{$i}
	{/for}


	{for $i = 0; $i < 10; $i++}
		{breakIf true}
		{continueIf true}
		{$i}
	{/for}

	EOD;

Assert::matchFile(
	__DIR__ . '/expected/for.phtml',
	$latte->compile($template),
);


// traversing
Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			For:
				Assign:
					Variable:
						name: i
					LNumber:
						value: 0
				BinaryOp:
					operator: <
					Variable:
						name: i
					LNumber:
						value: 10
				PostOp:
					Variable:
						name: i
				Fragment:
					Text:
						content: '...'
	XX, exportTraversing('{for $i = 0; $i < 10; $i++}...{/for}'));
