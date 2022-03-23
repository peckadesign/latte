<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\Compiler\Nodes\Php\Expr;
use Latte\Compiler\Nodes\Php\ExprNode;
use Latte\Compiler\Nodes\Php\Scalar;


/** @internal */
final class Block
{
	public string $method;
	public string $content;
	public string $context;

	/** @var Expr\AssignNode[] */
	public array $parameters = [];


	public function __construct(
		public /*readonly*/ ExprNode $name,
		public /*readonly*/ int|string $layer,
		public /*readonly*/ Tag $tag,
	) {
	}


	public function isDynamic(): bool
	{
		return !$this->name instanceof Scalar\StringNode
			&& !$this->name instanceof Scalar\LNumberNode;
	}
}
