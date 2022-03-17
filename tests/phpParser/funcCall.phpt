<?php

// Function calls

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* function name variations */
	a(),
	$a(),
	${'a'}(),
	$a['b'](),
	$a->b['c'](),

	/* array dereferencing */
	a()['b'],
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (6)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'a'
   |  |  |  |  line: 2
   |  |  |  args: array (0)
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 3
   |  |  |  args: array (0)
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'a'
   |  |  |  |  |  line: 4
   |  |  |  |  line: 4
   |  |  |  args: array (0)
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 5
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  line: 5
   |  |  |  |  line: 5
   |  |  |  args: array (0)
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  line: 6
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  line: 6
   |  |  |  |  line: 6
   |  |  |  args: array (0)
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'a'
   |  |  |  |  |  line: 9
   |  |  |  |  args: array (0)
   |  |  |  |  line: 9
   |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  value: 'b'
   |  |  |  |  line: 9
   |  |  |  line: 9
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   line: null
