<?php

// isset() and empty()

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	isset($a),
	isset($a, $b, $c),

	empty($a),
	empty(foo()),
	empty(array(1, 2, 3)),
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\IssetNode
   |  |  |  vars: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 2
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\EmptyNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\EmptyNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'foo'
   |  |  |  |  |  line: 5
   |  |  |  |  args: array (0)
   |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\EmptyNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 2
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 3
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: null
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   line: null
