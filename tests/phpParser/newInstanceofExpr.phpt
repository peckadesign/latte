<?php

// Arbitrary expressions in new and instanceof

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	new ('Foo' . $bar),
	new ('Foo' . $bar)($arg),
	$obj instanceof ('Foo' . $bar),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  line: 1
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  line: 1
   |  |  |  |  line: 1
   |  |  |  args: array (0)
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  line: 2
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  line: 2
   |  |  |  |  line: 2
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'arg'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  name: null
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\InstanceofNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'obj'
   |  |  |  |  line: 3
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  line: 3
   |  |  |  |  operator: '.'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'bar'
   |  |  |  |  |  line: 3
   |  |  |  |  line: 3
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   line: null
