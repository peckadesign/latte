<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Scalar;

use Latte\Compiler\Nodes\Php\ScalarNode;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;


class StringNode extends ScalarNode
{
	public function __construct(
		public string $value,
		public ?int $line = null,
	) {
	}


	public static function parse(string $str, int $line): self
	{
		$str = $str[0] === "'"
			? strtr(substr($str, 1, -1), ['\\\\' => '\\', "\\'" => "'"])
			: PhpHelpers::decodeEscapeSequences(substr($str, 1, -1), '"');
		return new self($str, $line);
	}


	public function print(PrintContext $context): string
	{
		return $context->encodeString($this->value);
	}
}
