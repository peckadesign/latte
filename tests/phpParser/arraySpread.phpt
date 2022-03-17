<?php

// Spread array

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$array = [1, 2, 3],

	[...[]],
	[...[1, 2, 3]],
	[...$array],
	[...getArr()],
	[...arrGen()],
	[...new ArrayIterator(['a', 'b', 'c'])],
	[0, ...$array, ...getArr(), 6, 7, 8, 9, 10, ...arrGen()],
	[0, ...$array, ...$array, 'end'],
	[(expand) [1, 2, 3]],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (10)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'array'
   |  |  |  |  line: 1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  line: null
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'getArr'
   |  |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 6
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'arrGen'
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 7
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'ArrayIterator'
   |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 8
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (9)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: true
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'getArr'
   |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: true
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 6
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 7
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'arrGen'
   |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 10
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 10
   |  |  |  |  |  unpack: true
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 10
   |  |  |  |  |  unpack: true
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'end'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 10
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 11
   |  |  |  |  |  unpack: true
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   line: null
