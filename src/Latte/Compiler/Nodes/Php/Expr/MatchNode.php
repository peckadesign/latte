<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\MatchArmNode;
use Latte\Compiler\PrintContext;


class MatchNode extends Php\ExprNode
{
	public function __construct(
		public Php\ExprNode $cond,
		/** @var MatchArmNode[] */
		public array $arms = [],
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$res = 'match (' . $this->cond->print($context) . ') {';
		foreach ($this->arms as $node) {
			$res .= "\n" . $node->print($context) . ',';
		}

		$res .= "\n}";
		return $res;
	}


	public function &getIterator(): \Generator
	{
		yield $this->cond;
		foreach ($this->arms as &$item) {
			yield $item;
		}
	}
}
