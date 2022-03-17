<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\Compiler\Nodes\Php\Expr;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;


class EncapsedStringNode extends ScalarNode
{
	public function __construct(
		/** @var ExprNode[] */
		public array $parts,
		public ?int $line = null,
	) {
	}


	/** @param ExprNode[] $parts */
	public static function parse(array $parts, int $line): self
	{
		foreach ($parts as $part) {
			if ($part instanceof EncapsedStringPartNode) {
				$part->value = PhpHelpers::decodeEscapeSequences($part->value, '"');
			}
		}

		return new self($parts, $line);
	}


	public function print(PrintContext $context): string
	{
		$s = '';
		$expr = false;
		foreach ($this->parts as $part) {
			if ($part instanceof EncapsedStringPartNode) {
				$s .= substr($context->encodeString($part->value, '"'), 1, -1);

			} elseif ($part instanceof Expr\VariableNode
				|| $part instanceof Expr\PropertyFetchNode
				|| $part instanceof Expr\NullsafePropertyFetchNode
				|| $part instanceof Expr\MethodCallNode
				|| $part instanceof Expr\NullsafeMethodCallNode
				|| $part instanceof Expr\ArrayAccessNode
			) {
				$s .= '{' . $part->print($context) . '}';

			} else {
				$s .= '" . (' . $part->print($context) . ') . "';
				$expr = true;
			}
		}

		return $expr
			? '("' . $s . '")'
			: '"' . $s . '"';
	}


	public function &getIterator(): \Generator
	{
		foreach ($this->parts as &$item) {
			yield $item;
		}
	}
}
