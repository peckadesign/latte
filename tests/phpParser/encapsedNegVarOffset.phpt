<?php

// Encapsed string negative var offsets

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	"$a[-0]",
	"$a[-1]",
	"$a[-0x0]",
	"$a[-00]",
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (4)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '-0'
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: -1
   |  |  |  |  |  |  kind: 10
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
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '-0x0'
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
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: '-00'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  line: 4
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   line: null
