<?php

// Constant fetches

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	A,
	A::B,
	A::class,
	$a::B,
	$a::class,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (5)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'A'
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  line: 2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'B'
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  line: 3
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'class'
   |  |  |  |  line: 3
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 4
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'B'
   |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'class'
   |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   line: null
