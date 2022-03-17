<?php

// Default parameter values

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function (
	    $b = null,
	    $c = 'foo',
	    $d = A::B,
	    $f = +1,
	    $g = -1.0,
	    $h = array(),
	    $i = [],
	    $j = ['foo'],
	    $k = ['foo', 'bar' => 'baz'],
	    $l = new Foo,
	) { return null; }
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (1)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (10)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'null'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  flags: 0
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  flags: 0
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  flags: 0
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\UnaryOpNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  flags: 0
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'g'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\UnaryOpNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\DNumberNode
   |  |  |  |  |  |  |  value: 1.0
   |  |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  operator: '-'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 6
   |  |  |  |  |  flags: 0
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'h'
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  line: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 7
   |  |  |  |  |  flags: 0
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'i'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  line: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 8
   |  |  |  |  |  flags: 0
   |  |  |  |  7 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'j'
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  flags: 0
   |  |  |  |  8 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'k'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  |  |  |  line: 10
   |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 10
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'baz'
   |  |  |  |  |  |  |  |  |  line: 10
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'bar'
   |  |  |  |  |  |  |  |  |  line: 10
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 10
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 10
   |  |  |  |  |  flags: 0
   |  |  |  |  9 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'l'
   |  |  |  |  |  |  line: 11
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'Foo'
   |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 11
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 11
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'null'
   |  |  |  |  |  line: 12
   |  |  |  |  line: 12
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   line: null
