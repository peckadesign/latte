<?php

// Array definitions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	array(),
	array('a'),
	array('a', ),
	array('a', 'b'),
	array('a', &$b, 'c' => 'd', 'e' => &$f),

	/* short array syntax */
	[],
	[1, 2, 3],
	['a' => 'b'],

	/* modern syntax */
	[a: 'b', x: 3],
	[y : 'c'],
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (0)
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (4)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: true
   |  |  |  |  |  line: 5
   |  |  |  |  |  unpack: false
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  unpack: false
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'e'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: true
   |  |  |  |  |  line: 5
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (0)
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  key: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 10
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  line: 13
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 13
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 13
   |  |  |  |  |  unpack: false
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 13
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  line: 13
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 13
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 13
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  items: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'c'
   |  |  |  |  |  |  line: 14
   |  |  |  |  |  key: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'y'
   |  |  |  |  |  |  line: 14
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 14
   |  |  |  |  |  unpack: false
   |  |  |  line: null
   |  |  key: null
   |  |  byRef: false
   |  |  line: 14
   |  |  unpack: false
   line: null
