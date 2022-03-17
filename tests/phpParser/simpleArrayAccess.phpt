<?php

// Simple array access

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a['b'],
	$a['b']['c'],
	$a[] = $b,
	${$a}['b'],
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 1
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 2
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  line: 2
   |  |  |  |  line: 2
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'c'
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 3
   |  |  |  |  index: null
   |  |  |  |  line: 3
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 3
   |  |  |  byRef: false
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 4
   |  |  |  |  line: 4
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   line: null
