<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\Compiler\Nodes\Php\ArgNode;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\Nodes\Php\VariadicPlaceholderNode;


abstract class CallLikeNode extends ExprNode
{
	/** @var array<ArgNode|VariadicPlaceholderNode> */
	public array $args = [];


	/** @return ArgNode[] */
	public static function argumentsFromValues(array $args): array
	{
		$res = [];
		foreach ($args as $key => $arg) {
			$arg = $arg instanceof ArgNode ? $arg : new ArgNode(self::fromValue($arg));
			if (\is_string($key)) {
				$arg->name = new IdentifierNode($key);
			}
			$res[] = $arg;
		}

		return $res;
	}


	public function isFirstClassCallable(): bool
	{
		foreach ($this->args as $arg) {
			if ($arg instanceof VariadicPlaceholderNode) {
				return true;
			}
		}

		return false;
	}


	/** @return ArgNode[] */
	public function getArgs(): array
	{
		assert(!$this->isFirstClassCallable());
		return $this->args;
	}
}
