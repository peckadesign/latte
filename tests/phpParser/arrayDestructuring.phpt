<?php

// Array destructuring

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	[$a, $b] = [$c, $d],
	[, $a, , , $b, ,] = $foo,
	[, [[$a]], $b] = $bar,
	['a' => $b, 'b' => $a] = $baz,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (4)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  byRef: false
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (6)
   |  |  |  |  |  0 => null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => null
   |  |  |  |  |  3 => null
   |  |  |  |  |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  5 => null
   |  |  |  |  line: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'foo'
   |  |  |  |  line: 2
   |  |  |  byRef: false
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'bar'
   |  |  |  |  line: 3
   |  |  |  byRef: false
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'baz'
   |  |  |  |  line: 4
   |  |  |  byRef: false
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   line: null
