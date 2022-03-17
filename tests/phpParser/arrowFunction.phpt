<?php

// Arrow Functions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	fn(bool $a) => $a,
	fn($x = 42) => $x,
	fn&($x) => $x,
	fn($x, ...$rest) => $rest,
	fn(): int => $x,

	fn($a, $b) => $a and $b,
	fn($a, $b) => $a && $b,
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
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'bool'
   |  |  |  |  |  |  line: 1
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
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  default: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 42
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: true
   |  |  |  params: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  line: 3
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'x'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  flags: 0
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'rest'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: true
   |  |  |  |  |  line: 4
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'rest'
   |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (0)
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'int'
   |  |  |  |  line: 5
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'x'
   |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  |  byRef: false
   |  |  |  |  params: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  |  type: null
   |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  default: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  flags: 0
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  |  type: null
   |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  default: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  variadic: false
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  flags: 0
   |  |  |  |  uses: array (0)
   |  |  |  |  returnType: null
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 7
   |  |  |  |  line: 7
   |  |  |  operator: 'and'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 7
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 8
   |  |  |  |  |  flags: 0
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 8
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: null
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 8
   |  |  |  |  operator: '&&'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 8
   |  |  |  |  line: 8
   |  |  |  line: 8
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   line: null
