<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\PrintContext;


class ParamNode extends Node
{
	public IdentifierNode|NameNode|ComplexTypeNode|null $type = null;


	public function __construct(
		public Expr\VariableNode $var,
		public ?ExprNode $default = null,
		string|IdentifierNode|NameNode|ComplexTypeNode|null $type = null,
		public bool $byRef = false,
		public bool $variadic = false,
		public ?int $line = null,
		public int $flags = 0,
	) {
		if ($variadic && $default !== null) {
			throw new CompileException('Variadic parameter cannot have a default value', $line);
		}
		$this->type = is_string($type) ? new IdentifierNode($type) : $type;
	}


	public function print(PrintContext $context): string
	{
		return ($this->type ? $this->type->print($context) . ' ' : '')
			. ($this->byRef ? '&' : '')
			. ($this->variadic ? '...' : '')
			. $this->var->print($context)
			. ($this->default ? ' = ' . $this->default->print($context) : '');
	}


	public function &getIterator(): \Generator
	{
		if ($this->type) {
			yield $this->type;
		}
		yield $this->var;
		if ($this->default) {
			yield $this->default;
		}
	}
}
