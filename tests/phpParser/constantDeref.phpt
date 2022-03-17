<?php

// Array/string dereferencing

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"abc"[2],
	"abc"[2][0][0],

	[1, 2, 3][2],
	[1, 2, 3][2][0][0],

	array(1, 2, 3)[2],
	array(1, 2, 3)[2][0][0],

	FOO[0],
	foo[0],
	x\foo[0],
	Foo::BAR[1],
	$foo::BAR[2][1][0],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (11)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'abc'
   |  |  |  |  line: 1
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'abc'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  line: 2
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  line: 2
   |  |  |  |  line: 2
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  line: 5
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  line: 5
   |  |  |  |  line: 5
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 2
   |  |  |  |  kind: 10
   |  |  |  |  line: 7
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  line: 8
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  line: 8
   |  |  |  |  line: 8
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 8
   |  |  |  line: 8
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'FOO'
   |  |  |  |  |  line: 10
   |  |  |  |  line: 10
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 10
   |  |  |  line: 10
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'foo'
   |  |  |  |  |  line: 11
   |  |  |  |  line: 11
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 11
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (2)
   |  |  |  |  |  |  0 => 'x'
   |  |  |  |  |  |  1 => 'foo'
   |  |  |  |  |  line: 12
   |  |  |  |  line: 12
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 12
   |  |  |  line: 12
   |  |  key: null
   |  |  byRef: false
   |  |  line: 12
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'Foo'
   |  |  |  |  |  line: 13
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'BAR'
   |  |  |  |  |  line: 13
   |  |  |  |  line: 13
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  line: 13
   |  |  |  line: 13
   |  |  key: null
   |  |  byRef: false
   |  |  line: 13
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  |  line: 14
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'BAR'
   |  |  |  |  |  |  |  line: 14
   |  |  |  |  |  |  line: 14
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 14
   |  |  |  |  |  line: 14
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  value: 1
   |  |  |  |  |  kind: 10
   |  |  |  |  |  line: 14
   |  |  |  |  line: 14
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 14
   |  |  |  line: 14
   |  |  key: null
   |  |  byRef: false
   |  |  line: 14
   |  |  unpack: false
   line: null
