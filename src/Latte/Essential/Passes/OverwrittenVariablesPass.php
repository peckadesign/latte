<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Passes;

use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\TemplateNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\Essential\Nodes\ForeachNode;


/**
 * Checks if foreach overrides template variables.
 */
final class OverwrittenVariablesPass
{
	use Latte\Strict;

	public static function process(TemplateNode $node, PrintContext $context): void
	{
		$vars = [];
		(new NodeTraverser)->traverse($node, function (Node $node) use (&$vars) {
			if ($node instanceof ForeachNode && $node->checkArgs) {
				preg_match('#.+\s+as\s*\$(\w+)(?:\s*=>\s*\$(\w+))?#i', $node->args->text, $m);
				for ($i = 1; $i < count($m); $i++) {
					$vars[$m[$i]][] = $node->line;
				}
			}
		});
		if ($vars) {
			array_unshift($node->head->children, new Latte\Compiler\Nodes\AuxiliaryNode(fn() => $context->format(
				<<<'XX'
					if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
						foreach (array_intersect_key(%dump, $this->params) as $ʟ_v => $ʟ_l) {
							trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
						}
					}

					XX,
				array_map(fn($l) => implode(', ', $l), $vars),
			)));
		}
	}
}
