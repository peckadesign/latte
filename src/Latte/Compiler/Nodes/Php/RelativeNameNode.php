<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\Compiler\PrintContext;


class RelativeNameNode extends NameNode
{
	public function isUnqualified(): bool
	{
		return false;
	}


	public function isQualified(): bool
	{
		return false;
	}


	public function isFullyQualified(): bool
	{
		return false;
	}


	public function isRelative(): bool
	{
		return true;
	}


	public function print(PrintContext $context): string
	{
		return 'namespace\\' . implode('\\', $this->parts);
	}
}
