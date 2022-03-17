<?php

// Ternary operator

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* ternary */
	$a ? $b : $c,
	$a ?: $c,

	/* precedence */
	$a ? $b : $c ? $d : $e,
	$a ? $b : ($c ? $d : $e),

	/* null coalesce */
	$a ?? $b,
	$a ?? $b ?? $c,
	$a ?? $b ? $c : $d,
	$a && $b ?? $c,

	/* short ternary */
	$a ? $b,
	$a ? $b ? $c,
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 2
   |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 2
   |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 3
   |  |  |  if: null
   |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 3
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 6
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 6
   |  |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 6
   |  |  |  |  line: 6
   |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'd'
   |  |  |  |  line: 6
   |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'e'
   |  |  |  |  line: 6
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 7
   |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 7
   |  |  |  else: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 7
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'd'
   |  |  |  |  |  line: 7
   |  |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'e'
   |  |  |  |  |  line: 7
   |  |  |  |  line: 7
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 10
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 10
   |  |  |  line: 10
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 11
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 11
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 11
   |  |  |  |  line: 11
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 12
   |  |  |  |  operator: '??'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 12
   |  |  |  |  line: 12
   |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 12
   |  |  |  else: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'd'
   |  |  |  |  line: 12
   |  |  |  line: 12
   |  |  key: null
   |  |  byRef: false
   |  |  line: 12
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 13
   |  |  |  |  operator: '&&'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 13
   |  |  |  |  line: 13
   |  |  |  operator: '??'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 13
   |  |  |  line: 13
   |  |  key: null
   |  |  byRef: false
   |  |  line: 13
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 16
   |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 16
   |  |  |  else: null
   |  |  |  line: 16
   |  |  key: null
   |  |  byRef: false
   |  |  line: 16
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 17
   |  |  |  if: Latte\Compiler\Nodes\Php\Expr\TernaryNode
   |  |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 17
   |  |  |  |  if: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 17
   |  |  |  |  else: null
   |  |  |  |  line: 17
   |  |  |  else: null
   |  |  |  line: 17
   |  |  key: null
   |  |  byRef: false
   |  |  line: 17
   |  |  unpack: false
   line: null
