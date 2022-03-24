<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Passes;

use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\Expr\FuncCallNode;
use Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode;
use Latte\Compiler\Nodes\Php\Expr\VariableNode;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\NodeTraverser;


/**
 * Enable custom functions.
 */
final class CustomFunctionsPass
{
	use Latte\Strict;

	public static function process(Node $node, array $functions): Node
	{
		$names = array_keys($functions);
		$names = array_combine(array_map('strtolower', $names), $names);

		return (new NodeTraverser)->traverse($node, function (Node $node) use ($names) {
			if ($node instanceof FuncCallNode
				&& $node->name instanceof NameNode
				&& ($orig = $names[strtolower((string) $node->name)] ?? null)
			) {
				if ((string) $node->name !== $orig) {
					trigger_error("Case mismatch on function name '{$node->name}', correct name is '$orig'.", E_USER_WARNING);
				}

				return new FuncCallNode(
					new PropertyFetchNode(new PropertyFetchNode(new PropertyFetchNode(new VariableNode('this'), 'global'), 'fn'), $orig),
					$node->args,
				);
			}
		});
	}
}
