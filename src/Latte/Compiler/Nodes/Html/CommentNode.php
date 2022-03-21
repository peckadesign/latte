<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\PrintContext;
use Latte\Context;


class CommentNode extends AreaNode
{
	public function __construct(
		public Node $content,
		public ?int $line = null,
	) {
	}


	public function print(PrintContext $context): string
	{
		$context->setEscapingContext(Context::HtmlComment);
		$content = $this->content->print($context);
		$context->setEscapingContext(Context::HtmlText);
		return "echo '<!--'; $content echo '-->';";
	}
}
