<?php

// Different integer syntaxes

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	0,
	1,
	0xFFF,
	0xfff,
	0XfFf,
	0777,
	0b111000111000,
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
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 0
   |  |  |  kind: 10
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 1
   |  |  |  kind: 10
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 4095
   |  |  |  kind: 16
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 4095
   |  |  |  kind: 16
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 4095
   |  |  |  kind: 16
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 511
   |  |  |  kind: 8
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  value: 3640
   |  |  |  kind: 2
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   line: null
