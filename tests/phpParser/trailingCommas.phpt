<?php

// PHP 7.3 trailing comma additions

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	foo($a, $b, ),
	$foo->bar($a, $b, ),
	Foo::bar($a, $b, ),
	new Foo($a, $b, ),
	unset($a, $b, ),
	isset($a, $b, ),
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
   |  |  |  |  |  0 => 'foo'
   |  |  |  |  line: 1
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 1
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 1
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 1
   |  |  |  |  |  name: null
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\MethodCallNode
   |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'foo'
   |  |  |  |  line: 2
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'bar'
   |  |  |  |  line: 2
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  name: null
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\StaticCallNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'Foo'
   |  |  |  |  line: 3
   |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'bar'
   |  |  |  |  line: 3
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  name: null
   |  |  |  line: 3
   |  |  key: null
   |  |  byRef: false
   |  |  line: 3
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'Foo'
   |  |  |  |  line: 4
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\FuncCallNode
   |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'unset'
   |  |  |  |  line: 5
   |  |  |  args: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  name: null
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ArgNode
   |  |  |  |  |  value: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  byRef: false
   |  |  |  |  |  unpack: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  name: null
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\IssetNode
   |  |  |  vars: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 6
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 6
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   line: null
