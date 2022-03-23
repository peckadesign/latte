<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Tags;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;


/**
 * n:class="..."
 */
final class NClassAttribute
{
	use Latte\Strict;

	public static function parse(Tag $tag): void
	{
		if ($tag->htmlElement->getAttribute('class')) {
			throw new CompileException('It is not possible to combine class with n:class.', $tag->line);
		}

		$tag->expectArguments();
		$tag->htmlElement->attrs->append(new AuxiliaryNode(
			fn(PrintContext $context) => $context->format(
				'echo ($ʟ_tmp = array_filter(%array)) ? \' class="\' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . \'"\' : "" %line;',
				$tag->tokenizer,
				$tag->line,
			),
			'n:class',
		));
	}
}
