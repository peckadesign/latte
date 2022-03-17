<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class IssetNode extends ExprNode
{
	public function __construct(
		/** @var ExprNode[] */
		public array $vars,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		return 'isset(' . $context->implode($this->vars) . ')';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->vars as &$item) {
			yield $item;
		}
	}
}
