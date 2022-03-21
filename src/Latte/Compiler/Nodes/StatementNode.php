<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes;


abstract class StatementNode extends ContentNode
{
	public const
		OutputNone = 0,
		OutputInline = 1,
		OutputBlock = 2;


	public function getOutputMode(): int
	{
		return self::OutputNone;
	}
}
