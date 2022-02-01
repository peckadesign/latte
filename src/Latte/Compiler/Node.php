<?php

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Strict;


/**
 * @implements \IteratorAggregate<Node>
 */
abstract class Node implements \IteratorAggregate
{
	use Strict;

	public ?int $line = null;


	abstract public function print(PrintContext $context): string;


	public function &getIterator(): \Generator
	{
		return;
		yield;
	}
}
