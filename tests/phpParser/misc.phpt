<?php

// Uniform variable syntax in PHP 7 (misc)

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"string"->length(),
	"foo$bar"[0],
	"foo$bar"->length(),
	(clone $obj)->b[0](1),
	[0, 1][0] = 1,
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MethodCallNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'string'
   |  |  |  |  line: 1
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  line: 1
   |  |  |  args: array (0)
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  |  parts: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  line: 2
   |  |  |  |  line: 2
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 0
   |  |  |  |  kind: 10
   |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MethodCallNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  |  parts: array (2)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  |  value: 'foo'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'bar'
   |  |  |  |  |  |  line: 3
   |  |  |  |  line: 3
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'length'
   |  |  |  |  line: 3
   |  |  |  args: array (0)
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\CloneNode
   |  |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'obj'
   |  |  |  |  |  |  |  line: 4
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  line: 4
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  line: 4
   |  |  |  |  line: 4
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  name: null
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  line: 5
   |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  line: null
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  value: 0
   |  |  |  |  |  kind: 10
   |  |  |  |  |  line: 5
   |  |  |  |  line: 5
   |  |  |  expr: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  value: 1
   |  |  |  |  kind: 10
   |  |  |  |  line: 5
   |  |  |  byRef: false
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   line: null
