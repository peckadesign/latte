<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Passes;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\Expr\VariableNode;
use Latte\Compiler\NodeTraverser;


/**
 * $ʟ_xxx variables are forbidden
 */
final class InternalVariablesPass
{
	use Latte\Strict;

	public static function process(Node $node): Node
	{
		return (new NodeTraverser)->traverse($node, function (Node $node) {
			if ($node instanceof VariableNode
				&& is_string($node->name)
				&& (str_starts_with($node->name, 'ʟ_'))
			) {
				throw new CompileException("Forbidden variable \$$node->name.", $node->line);
			}
		});
	}
}
