<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\PrintContext;


class ClosureNode extends ExprNode
{
	public function __construct(
		public bool $byRef,
		/** @var Php\ParamNode[] */
		public array $params,
		/** @var ClosureUseNode[] */
		public array $uses,
		public Php\IdentifierNode|Php\NameNode|Php\ComplexTypeNode|null $returnType = null,
		public ?ExprNode $expr,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$arrow = (bool) $this->expr;
		foreach ($this->uses as $use) {
			$arrow = $arrow && !$use->byRef;
		}

		return $arrow
			? 'fn' . ($this->byRef ? '&' : '')
				. '(' . $context->implode($this->params) . ')'
				. ($this->returnType !== null ? ': ' . $this->returnType->print($context) : '')
				. ' => '
				. $this->expr->print($context)
			: 'function ' . ($this->byRef ? '&' : '')
				. '(' . $context->implode($this->params) . ')'
				. (!empty($this->uses) ? ' use (' . $context->implode($this->uses) . ')' : '')
				. ($this->returnType !== null ? ' : ' . $this->returnType->print($context) : '')
				. ($this->expr ? ' { return ' . $this->expr->print($context) . '; }' : ' {}');
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->params as &$item) {
			yield $item;
		}

		foreach ($this->uses as &$item) {
			yield $item;
		}

		if ($this->returnType) {
			yield $this->returnType;
		}
		if ($this->expr) {
			yield $this->expr;
		}
	}
}
