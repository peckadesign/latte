<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential\Tags;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Nodes\Php\Expr\StaticCallNode;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Tag;


/**
 * n:tag="..."
 */
final class NTagAttribute
{
	use Latte\Strict;

	public static function parse(Tag $tag): void
	{
		if (preg_match('(style$|script$)iA', $tag->htmlElement->name)) {
			throw new CompileException('Attribute n:tag is not allowed in <script> or <style>', $tag->line);
		}

		$tag->expectArguments();
		$args = $tag->parser->parseExpression();
		$tag->htmlElement->variableName = StaticCallNode::from(
			self::class,
			'check',
			[new Scalar\StringNode($tag->htmlElement->name), $args],
		);
	}


	/** @internal */
	public static function check(string $orig, $new): string
	{
		if ($new === null) {
			return $orig;

		} elseif (
			!is_string($new)
			|| !preg_match('~' . Latte\Compiler\TemplateLexer::ReTagName . '$~DA', $new)
		) {
			throw new Latte\RuntimeException('Invalid tag name ' . var_export($new, true));

		} elseif (
			in_array($lower = strtolower($new), ['style', 'script'], true)
			|| isset(Latte\Helpers::$emptyElements[strtolower($orig)]) !== isset(Latte\Helpers::$emptyElements[$lower])
		) {
			throw new Latte\RuntimeException("Forbidden tag <$orig> change to <$new>.");
		}

		return $new;
	}
}
