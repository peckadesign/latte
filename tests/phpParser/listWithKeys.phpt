<?php

// List destructing with keys

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	list('a' => $b) = ['a' => 'b'],
	list('a' => list($b => $c), 'd' => $e) = $x,
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
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: 1
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  line: 1
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
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  items: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  value: 'd'
   |  |  |  |  |  |  |  line: 2
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
   line: null
