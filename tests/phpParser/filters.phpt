<?php

// Filters

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	($a|upper),
	($a . $b |upper|truncate),
	($a |truncate: 10, 20|trim),
	($a |truncate: 10, (20|round)|trim),
	($a |truncate: a: 10, b: true),
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 1
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'upper'
   |  |  |  |  line: 1
   |  |  |  args: array (0)
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  operator: '.'
   |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  line: 2
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'upper'
   |  |  |  |  |  line: 2
   |  |  |  |  args: array (0)
   |  |  |  |  line: 2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'truncate'
   |  |  |  |  line: 2
   |  |  |  args: array (0)
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 3
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  line: 3
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  name: null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 3
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'trim'
   |  |  |  |  line: 3
   |  |  |  args: array (0)
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 4
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'truncate'
   |  |  |  |  |  line: 4
   |  |  |  |  args: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  name: null
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  |  |  |  |  inner: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  value: 20
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'round'
   |  |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  |  args: array (0)
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  name: null
   |  |  |  |  line: 4
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'trim'
   |  |  |  |  line: 4
   |  |  |  args: array (0)
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FilterNode
   |  |  |  inner: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 5
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'truncate'
   |  |  |  |  line: 5
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 10
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 5
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'true'
   |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   line: null
