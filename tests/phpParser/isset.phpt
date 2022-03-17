<?php

// UVS isset() on temporaries

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	isset(([0, 1] + [])[0]),
	isset(['a' => 'b']->a),
	isset("str"->a),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (3)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\BinaryOpNode
   |  |  |  |  |  |  left: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  items: array (2)
   |  |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  |  |  |  value: 1
   |  |  |  |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  |  |  |  key: null
   |  |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  |  line: 1
   |  |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  operator: '+'
   |  |  |  |  |  |  right: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  |  items: array (0)
   |  |  |  |  |  |  |  line: null
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  line: 1
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayNode
   |  |  |  |  |  |  items: array (1)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  |  |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'b'
   |  |  |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  |  |  key: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  |  |  |  value: 'a'
   |  |  |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  |  |  byRef: false
   |  |  |  |  |  |  |  |  line: 2
   |  |  |  |  |  |  |  |  unpack: false
   |  |  |  |  |  |  line: null
   |  |  |  |  |  line: 2
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\IssetNode
   |  |  |  vars: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  |  value: 'str'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  line: 3
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   line: null
