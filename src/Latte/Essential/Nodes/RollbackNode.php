<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Nodes;

use Latte\CompileException;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * {rollback}
 */
class RollbackNode extends StatementNode
{
	public static function create(Tag $tag): self
	{
		if (!$tag->closest(['try'])) {
			throw new CompileException('Tag {rollback} must be inside {try} ... {/try}.', $tag->line);
		}

		return new self;
	}


	public function print(PrintContext $context): string
	{
		return 'throw new Latte\Essential\RollbackException;';
	}
}
