<?php

// Match

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	match (1) {
	    0 => 'Foo',
	    1 => 'Bar',
	},

	match (1) {
	    /* list of conditions */
	    0, 1 => 'Foo',
	},

	match ($operator) {
	    BinaryOperator::ADD => $lhs + $rhs,
	},

	match ($char) {
	    1 => '1',
	    default => 'default'
	},

	match (1) {
	    0, 1, => 'Foo',
	    default, => 'Bar',
	},
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  line: 1
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  line: 2
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Bar'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  line: 3
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  line: 6
   |  |  |  arms: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  line: 8
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'operator'
   |  |  |  |  line: 11
   |  |  |  arms: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ClassConstFetchNode
   |  |  |  |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'BinaryOperator'
   |  |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'ADD'
   |  |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'lhs'
   |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'rhs'
   |  |  |  |  |  |  |  line: 12
   |  |  |  |  |  |  line: 12
   |  |  |  |  |  line: 12
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'char'
   |  |  |  |  line: 15
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (1)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 16
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '1'
   |  |  |  |  |  |  line: 16
   |  |  |  |  |  line: 16
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: null
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'default'
   |  |  |  |  |  |  line: 17
   |  |  |  |  |  line: 17
   |  |  |  line: 15
   |  |  key: null
   |  |  byRef: false
   |  |  line: 15
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MatchNode
   |  |  |  cond: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  line: 20
   |  |  |  arms: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 21
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 21
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Foo'
   |  |  |  |  |  |  line: 21
   |  |  |  |  |  line: 21
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\MatchArmNode
   |  |  |  |  |  conds: null
   |  |  |  |  |  body: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'Bar'
   |  |  |  |  |  |  line: 22
   |  |  |  |  |  line: 22
   |  |  |  line: 20
   |  |  key: null
   |  |  byRef: false
   |  |  line: 20
   |  |  unpack: false
   line: null
