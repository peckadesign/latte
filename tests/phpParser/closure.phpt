<?php

// Closures

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function ($a) { return $a; },
	function ($a) { return $a; },
	function ($a) use ($b) { },
	function () use ($a, &$b) { return; },
	function &($a) { return; },
	function ($a) : array { return null; },
	function () use ($a,) : \Foo\Bar { return null; },
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (7)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 1
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 3
   |  |  |  returnType: null
   |  |  |  expr: null
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (0)
   |  |  |  uses: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 4
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  byRef: true
   |  |  |  |  |  line: 4
   |  |  |  returnType: null
   |  |  |  expr: null
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: true
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: null
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 6
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'array'
   |  |  |  |  line: 6
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'null'
   |  |  |  |  |  line: 6
   |  |  |  |  line: 6
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (0)
   |  |  |  uses: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ClosureUseNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  byRef: false
   |  |  |  |  |  line: 7
   |  |  |  returnType: Latte\Compiler\Nodes\Php\FullyQualifiedNameNode
   |  |  |  |  parts: array (2)
   |  |  |  |  |  0 => 'Foo'
   |  |  |  |  |  1 => 'Bar'
   |  |  |  |  line: 7
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'null'
   |  |  |  |  |  line: 7
   |  |  |  |  line: 7
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   line: null
