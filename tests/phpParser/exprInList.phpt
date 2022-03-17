<?php

// Expressions in list()

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* This is legal. */
	list(($a), ((($b)))) = $x,
	/* This is illegal, but not a syntax error. */
	list(1 + 1) = $x,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (2)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: 2
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  line: 2
   |  |  |  byRef: false
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: 4
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  line: 4
   |  |  |  byRef: false
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   line: null
