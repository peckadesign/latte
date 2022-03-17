<?php

// UVS indirect calls

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	id('var_dump')(1),

	id('id')('var_dump')(2),

	id()()('var_dump')(4),

	id(['udef', 'id'])[1]()('var_dump')(5),

	(function($x) { return $x; })('id')('var_dump')(8),

	($f = function($x = null) use (&$f) {
	    return $x ?: $f;
	})()()()('var_dump')(9),

	[$obj, 'id']()('id')($id)('var_dump')(10),

	'id'()('id')('var_dump')(12),

	('i' . 'd')()('var_dump')(13),

	'\id'('var_dump')(14),
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  line: 1
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 1
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 1
   |  |  |  |  |  name: null
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  line: 3
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 3
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  name: null
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  line: 5
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 5
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 4
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  name: null
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'id'
   |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'udef'
   |  |  |  |  |  |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  line: 7
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 7
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 5
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 7
   |  |  |  |  |  name: null
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  |  |  default: null
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  |  |  flags: 0
   |  |  |  |  |  |  uses: array (0)
   |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  line: 9
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 9
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 8
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 9
   |  |  |  |  |  name: null
   |  |  |  line: 9
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  params: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  |  |  |  |  |  |  type: null
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  |  |  |  |  |  0 => 'null'
   |  |  |  |  |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  |  |  |  flags: 0
   |  |  |  |  |  |  |  |  |  uses: array (1)
   |  |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ClosureUseNode
   |  |  |  |  |  |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  |  |  |  byRef: true
   |  |  |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  |  returnType: null
   |  |  |  |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  |  |  |  |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  |  |  |  |  if: null
   |  |  |  |  |  |  |  |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  line: 11
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 11
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  line: 11
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 13
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 13
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 11
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 9
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 13
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 13
   |  |  |  |  |  name: null
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  |  name: 'obj'
   |  |  |  |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  |  name: null
   |  |  |  |  |  |  line: 15
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  name: 'id'
   |  |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  line: 15
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 15
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 15
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 15
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 15
   |  |  |  |  |  name: null
   |  |  |  line: 15
   |  |  key: null
   |  |  byRef: false
   |  |  line: 15
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  line: 17
   |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  line: 17
   |  |  |  |  |  args: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  value: 'id'
   |  |  |  |  |  |  |  |  line: 17
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: 17
   |  |  |  |  |  |  |  name: null
   |  |  |  |  |  line: 17
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 17
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 17
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 17
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 12
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 17
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 17
   |  |  |  |  |  name: null
   |  |  |  line: 17
   |  |  key: null
   |  |  byRef: false
   |  |  line: 17
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'i'
   |  |  |  |  |  |  |  line: 19
   |  |  |  |  |  |  operator: '.'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  line: 19
   |  |  |  |  |  |  line: 19
   |  |  |  |  |  args: array (0)
   |  |  |  |  |  line: 19
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 19
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 19
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 19
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 13
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 19
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 19
   |  |  |  |  |  name: null
   |  |  |  line: 19
   |  |  key: null
   |  |  byRef: false
   |  |  line: 19
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: '\id'
   |  |  |  |  |  line: 21
   |  |  |  |  args: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'var_dump'
   |  |  |  |  |  |  |  line: 21
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 21
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 21
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 14
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 21
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 21
   |  |  |  |  |  name: null
   |  |  |  line: 21
   |  |  key: null
   |  |  byRef: false
   |  |  line: 21
   |  |  unpack: false
   line: null
