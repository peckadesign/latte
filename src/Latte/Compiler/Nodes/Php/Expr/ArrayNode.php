<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class ArrayNode extends ExprNode
{
	public function __construct(
		/** @var ArrayItemNode[] */
		public array $items = [],
		public ?int $line = null,
	) {
	}


	public static function fromArray(array $arr): self
	{
		$node = new self;
		$lastKey = -1;
		foreach ($arr as $key => $val) {
			if ($lastKey !== null && ++$lastKey === $key) {
				$node->items[] = new ArrayItemNode(self::fromValue($val));
			} else {
				$lastKey = null;
				$node->items[] = new ArrayItemNode(self::fromValue($val), self::fromValue($key));
			}
		}

		return $node;
	}


	public function print(PrintContext $context): string
	{
		return '[' . $context->implode($this->items) . ']';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->items as &$item) {
			yield $item;
		}
	}
}
