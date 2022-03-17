<?php

// Mathematical operators

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* unary ops */
	~$a,
	+$a,
	-$a,

	/* binary ops */
	$a & $b,
	$a ^ $b,
	$a . $b,
	$a / $b,
	$a - $b,
	$a % $b,
	$a * $b,
	$a + $b,
	$a << $b,
	$a >> $b,
	$a ** $b,

	/* associativity */
	$a * $b * $c,
	$a * ($b * $c),

	/* precedence */
	$a + $b * $c,
	($a + $b) * $c,

	/* pow is special */
	$a ** $b ** $c,
	($a ** $b) ** $c,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (20)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 2
   |  |  |  operator: '~'
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 3
   |  |  |  operator: '+'
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\UnaryOpNode
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 4
   |  |  |  operator: '-'
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 7
   |  |  |  operator: '&'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
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
   |  |  |  |  line: 8
   |  |  |  operator: '^'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 8
   |  |  |  line: 8
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 9
   |  |  |  operator: '.'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 9
   |  |  |  line: 9
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 10
   |  |  |  operator: '/'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 10
   |  |  |  line: 10
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 11
   |  |  |  operator: '-'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 11
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 12
   |  |  |  operator: '%'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 12
   |  |  |  line: 12
   |  |  key: null
   |  |  byRef: false
   |  |  line: 12
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 13
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 13
   |  |  |  line: 13
   |  |  key: null
   |  |  byRef: false
   |  |  line: 13
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 14
   |  |  |  operator: '+'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 14
   |  |  |  line: 14
   |  |  key: null
   |  |  byRef: false
   |  |  line: 14
   |  |  unpack: false
   |  11 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 15
   |  |  |  operator: '<<'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 15
   |  |  |  line: 15
   |  |  key: null
   |  |  byRef: false
   |  |  line: 15
   |  |  unpack: false
   |  12 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 16
   |  |  |  operator: '>>'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 16
   |  |  |  line: 16
   |  |  key: null
   |  |  byRef: false
   |  |  line: 16
   |  |  unpack: false
   |  13 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 17
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 17
   |  |  |  line: 17
   |  |  key: null
   |  |  byRef: false
   |  |  line: 17
   |  |  unpack: false
   |  14 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 20
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 20
   |  |  |  |  line: 20
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 20
   |  |  |  line: 20
   |  |  key: null
   |  |  byRef: false
   |  |  line: 20
   |  |  unpack: false
   |  15 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 21
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 21
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 21
   |  |  |  |  line: 21
   |  |  |  line: 21
   |  |  key: null
   |  |  byRef: false
   |  |  line: 21
   |  |  unpack: false
   |  16 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 24
   |  |  |  operator: '+'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 24
   |  |  |  |  operator: '*'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 24
   |  |  |  |  line: 24
   |  |  |  line: 24
   |  |  key: null
   |  |  byRef: false
   |  |  line: 24
   |  |  unpack: false
   |  17 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 25
   |  |  |  |  operator: '+'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 25
   |  |  |  |  line: 25
   |  |  |  operator: '*'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 25
   |  |  |  line: 25
   |  |  key: null
   |  |  byRef: false
   |  |  line: 25
   |  |  unpack: false
   |  18 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 28
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 28
   |  |  |  |  operator: '**'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 28
   |  |  |  |  line: 28
   |  |  |  line: 28
   |  |  key: null
   |  |  byRef: false
   |  |  line: 28
   |  |  unpack: false
   |  19 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  left: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 29
   |  |  |  |  operator: '**'
   |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 29
   |  |  |  |  line: 29
   |  |  |  operator: '**'
   |  |  |  right: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 29
   |  |  |  line: 29
   |  |  key: null
   |  |  byRef: false
   |  |  line: 29
   |  |  unpack: false
   line: null
