<?php

// Assignments

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* simple assign */
	$a = $b,

	/* combined assign */
	$a &= $b,
	$a |= $b,
	$a ^= $b,
	$a .= $b,
	$a /= $b,
	$a -= $b,
	$a %= $b,
	$a *= $b,
	$a += $b,
	$a <<= $b,
	$a >>= $b,
	$a **= $b,
	$a ??= $b,

	/* chained assign */
	$a = $b *= $c **= $d,

	/* by ref assign */
	$a =& $b,

	/* list() assign */
	list($a) = $b,
	list($a, , $b) = $c,
	list($a, list(, $c), $d) = $e,

	/* inc/dec */
	++$a,
	$a++,
	--$a,
	$a--,
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (23)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 2
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 2
   |  |  |  byRef: false
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 5
   |  |  |  operator: '&'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 5
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 6
   |  |  |  operator: '|'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 6
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 7
   |  |  |  operator: '^'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 7
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 8
   |  |  |  operator: '.'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 8
   |  |  |  line: 8
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 9
   |  |  |  operator: '/'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 9
   |  |  |  line: 9
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 10
   |  |  |  operator: '-'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 10
   |  |  |  line: 10
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 11
   |  |  |  operator: '%'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 11
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 12
   |  |  |  operator: '*'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 12
   |  |  |  line: 12
   |  |  key: null
   |  |  byRef: false
   |  |  line: 12
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 13
   |  |  |  operator: '+'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 13
   |  |  |  line: 13
   |  |  key: null
   |  |  byRef: false
   |  |  line: 13
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 14
   |  |  |  operator: '<<'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 14
   |  |  |  line: 14
   |  |  key: null
   |  |  byRef: false
   |  |  line: 14
   |  |  unpack: false
   |  11 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 15
   |  |  |  operator: '>>'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 15
   |  |  |  line: 15
   |  |  key: null
   |  |  byRef: false
   |  |  line: 15
   |  |  unpack: false
   |  12 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 16
   |  |  |  operator: '**'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 16
   |  |  |  line: 16
   |  |  key: null
   |  |  byRef: false
   |  |  line: 16
   |  |  unpack: false
   |  13 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 17
   |  |  |  operator: '??'
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 17
   |  |  |  line: 17
   |  |  key: null
   |  |  byRef: false
   |  |  line: 17
   |  |  unpack: false
   |  14 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 20
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 20
   |  |  |  |  operator: '*'
   |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\AssignOpNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  line: 20
   |  |  |  |  |  operator: '**'
   |  |  |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  line: 20
   |  |  |  |  |  line: 20
   |  |  |  |  line: 20
   |  |  |  byRef: false
   |  |  |  line: 20
   |  |  key: null
   |  |  byRef: false
   |  |  line: 20
   |  |  unpack: false
   |  15 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 23
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 23
   |  |  |  byRef: true
   |  |  |  line: 23
   |  |  key: null
   |  |  byRef: false
   |  |  line: 23
   |  |  unpack: false
   |  16 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  items: array (1)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 26
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 26
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: 26
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'b'
   |  |  |  |  line: 26
   |  |  |  byRef: false
   |  |  |  line: 26
   |  |  key: null
   |  |  byRef: false
   |  |  line: 26
   |  |  unpack: false
   |  17 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 27
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 27
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => null
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  |  line: 27
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 27
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: 27
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'c'
   |  |  |  |  line: 27
   |  |  |  byRef: false
   |  |  |  line: 27
   |  |  key: null
   |  |  byRef: false
   |  |  line: 27
   |  |  unpack: false
   |  18 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\AssignNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  items: array (3)
   |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\ListNode
   |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  0 => null
   |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  unpack: false
   |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  key: null
   |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  line: 28
   |  |  |  |  |  |  unpack: false
   |  |  |  |  line: 28
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'e'
   |  |  |  |  line: 28
   |  |  |  byRef: false
   |  |  |  line: 28
   |  |  key: null
   |  |  byRef: false
   |  |  line: 28
   |  |  unpack: false
   |  19 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\PreOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 31
   |  |  |  operator: '++'
   |  |  |  line: 31
   |  |  key: null
   |  |  byRef: false
   |  |  line: 31
   |  |  unpack: false
   |  20 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\PostOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 32
   |  |  |  operator: '++'
   |  |  |  line: 32
   |  |  key: null
   |  |  byRef: false
   |  |  line: 32
   |  |  unpack: false
   |  21 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\PreOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 33
   |  |  |  operator: '--'
   |  |  |  line: 33
   |  |  key: null
   |  |  byRef: false
   |  |  line: 33
   |  |  unpack: false
   |  22 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\PostOpNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 34
   |  |  |  operator: '--'
   |  |  |  line: 34
   |  |  key: null
   |  |  byRef: false
   |  |  line: 34
   |  |  unpack: false
   line: null
