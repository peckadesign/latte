<?php

// Encapsed strings

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"$A",
	"$A->B",
	"$A[B]",
	"$A[0]",
	"$A[1234]",
	"$A[9223372036854775808]",
	"$A[000]",
	"$A[0x0]",
	"$A[0b0]",
	"$A[$B]",
	"{$A}",
	"{$A['B']}",
	"\{$A}",
	"\{ $A }",
	"\\{$A}",
	"\\{ $A }",
	"$$A[B]",
	"A $B C",
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (18)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  line: 3
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 1234
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '9223372036854775808'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  line: 6
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '000'
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  line: 7
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0x0'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  line: 8
   |  |  |  line: 8
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '0b0'
   |  |  |  |  |  |  line: 9
   |  |  |  |  |  line: 9
   |  |  |  line: 9
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'B'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  line: 10
   |  |  |  line: 10
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  line: 11
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  11 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 12
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  line: 12
   |  |  |  |  |  line: 12
   |  |  |  line: 12
   |  |  key: null
   |  |  byRef: false
   |  |  line: 12
   |  |  unpack: false
   |  12 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: '\{'
   |  |  |  |  |  line: 13
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  line: 13
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: '}'
   |  |  |  |  |  line: 13
   |  |  |  line: 13
   |  |  key: null
   |  |  byRef: false
   |  |  line: 13
   |  |  unpack: false
   |  13 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  line: 14
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  line: 14
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  line: 14
   |  |  |  line: 14
   |  |  key: null
   |  |  byRef: false
   |  |  line: 14
   |  |  unpack: false
   |  14 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: '\'
   |  |  |  |  |  line: 15
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  line: 15
   |  |  |  line: 15
   |  |  key: null
   |  |  byRef: false
   |  |  line: 15
   |  |  unpack: false
   |  15 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: '\{ '
   |  |  |  |  |  line: 16
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'A'
   |  |  |  |  |  line: 16
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' }'
   |  |  |  |  |  line: 16
   |  |  |  line: 16
   |  |  key: null
   |  |  byRef: false
   |  |  line: 16
   |  |  unpack: false
   |  16 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: '$'
   |  |  |  |  |  line: 17
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'A'
   |  |  |  |  |  |  line: 17
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'B'
   |  |  |  |  |  |  line: 17
   |  |  |  |  |  line: 17
   |  |  |  line: 17
   |  |  key: null
   |  |  byRef: false
   |  |  line: 17
   |  |  unpack: false
   |  17 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (3)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: 'A '
   |  |  |  |  |  line: 18
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'B'
   |  |  |  |  |  line: 18
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' C'
   |  |  |  |  |  line: 18
   |  |  |  line: 18
   |  |  key: null
   |  |  byRef: false
   |  |  line: 18
   |  |  unpack: false
   line: null
