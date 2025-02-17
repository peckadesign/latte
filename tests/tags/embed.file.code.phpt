<?php

/**
 * Test: {embed file}
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader([
	'main' => <<<'XX'

				{embed "embed1.latte"}
					{block a}
						{embed "embed2.latte"}
							{block a}nested embeds A{/block}
						{/embed}
					{/block}
					{import import.latte}
				{/embed}

		XX,
	'embed1.latte' => <<<'XX'

				embed1-start
					{block a}embed1-A{/block}
				embed1-end

		XX,
	'embed2.latte' => <<<'XX'

				embed2-start
					{block a}embed2-A{/block}
				embed2-end

		XX,
]));

Assert::matchFile(
	__DIR__ . '/expected/embed.file.phtml',
	$latte->compile('main'),
);


// traversing
Assert::match(<<<'XX'
	Template:
		Fragment:
		Fragment:
			Embed:
				String:
					value: foo.latte
				Array:
					ArrayItem:
						Assign:
							Variable:
								name: var
							LNumber:
								value: 10
				Fragment:
					Text:
						content: ' '
					Block:
						String:
							value: a
						Fragment:
					Text:
						content: ' '
	XX, exportTraversing('{embed foo.latte, $var = 10} {block a/} {/embed}'));
