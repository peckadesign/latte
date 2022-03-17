<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;


class ArrayItemNode extends ExprNode
{
	public function __construct(
		public ExprNode $value,
		public ExprNode|IdentifierNode|null $key = null,
		public bool $byRef = false,
		public ?int $line = null,
		public bool $unpack = false,
	) {
	}


	public function print(PrintContext $context): string
	{
		$key = match (true) {
			$this->key instanceof ExprNode => $this->key->print($context) . ' => ',
				$this->key instanceof IdentifierNode => $context->encodeString($this->key->name) . ' => ',
				$this->key === null => '',
		};
		return $key
			. ($this->byRef ? '&' : '')
			. ($this->unpack ? '...' : '')
			. $this->value->print($context);
	}


	public function &getIterator(): \Generator
	{
		if ($this->key) {
			yield $this->key;
		}
		yield $this->value;
	}
}
