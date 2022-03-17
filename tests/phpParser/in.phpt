<?php

// In operators

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	$a in $b,

	/* precedence */
	$a in $b || $c in $d,
	$a = $b in $c,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\InRangeNode
   |  |  |  needle: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 1
   |  |  |  haystack: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\InRangeNode
   |  |  |  |  needle: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 4
   |  |  |  |  haystack: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 4
   |  |  |  |  line: 4
   |  |  |  operator: '||'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\InRangeNode
   |  |  |  |  needle: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 4
   |  |  |  |  haystack: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'd'
   |  |  |  |  |  line: 4
   |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 5
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\InRangeNode
   |  |  |  |  needle: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 5
   |  |  |  |  haystack: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 5
   |  |  |  |  line: 5
   |  |  |  byRef: false
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   line: null
