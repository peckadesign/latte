<?php

// New

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	new A,
	new A($b),

	/* class name variations */
	new $a(),
	new $a['b'](),
	new A::$b(),
	/* DNCR object access */
	new $a->b(),
	new $a->b->c(),
	new $a->b['c'](),

	/* UVS new expressions */
	new $className,
	new $array['className'],
	new $obj->className,
	new Test::$className,
	new $test::$className,
	new $weird[0]->foo::$className,

	/* test regression introduces by new dereferencing syntax */
	(new A),
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (15)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  line: 1
   |  |  |  args: array (0)
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  line: 2
   |  |  |  args: array (1)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ArgNode
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
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'a'
   |  |  |  |  line: 5
   |  |  |  args: array (0)
   |  |  |  line: 5
   |  |  key: null
   |  |  byRef: false
   |  |  line: 5
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 6
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'b'
   |  |  |  |  |  line: 6
   |  |  |  |  line: 6
   |  |  |  args: array (0)
   |  |  |  line: 6
   |  |  key: null
   |  |  byRef: false
   |  |  line: 6
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\StaticPropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 7
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  line: 7
   |  |  |  |  line: 7
   |  |  |  args: array (0)
   |  |  |  line: 7
   |  |  key: null
   |  |  byRef: false
   |  |  line: 7
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'b'
   |  |  |  |  |  line: 9
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 9
   |  |  |  |  line: 9
   |  |  |  args: array (0)
   |  |  |  line: 9
   |  |  key: null
   |  |  byRef: false
   |  |  line: 9
   |  |  unpack: false
   |  6 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'c'
   |  |  |  |  |  line: 10
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 10
   |  |  |  |  |  line: 10
   |  |  |  |  line: 10
   |  |  |  args: array (0)
   |  |  |  line: 10
   |  |  key: null
   |  |  byRef: false
   |  |  line: 10
   |  |  unpack: false
   |  7 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 11
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 11
   |  |  |  |  |  line: 11
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'c'
   |  |  |  |  |  line: 11
   |  |  |  |  line: 11
   |  |  |  args: array (0)
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  8 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  name: 'className'
   |  |  |  |  line: 14
   |  |  |  args: array (0)
   |  |  |  line: 14
   |  |  key: null
   |  |  byRef: false
   |  |  line: 14
   |  |  unpack: false
   |  9 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'array'
   |  |  |  |  |  line: 15
   |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  |  |  value: 'className'
   |  |  |  |  |  line: 15
   |  |  |  |  line: 15
   |  |  |  args: array (0)
   |  |  |  line: 15
   |  |  key: null
   |  |  byRef: false
   |  |  line: 15
   |  |  unpack: false
   |  10 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  line: 16
   |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'obj'
   |  |  |  |  |  line: 16
   |  |  |  |  line: 16
   |  |  |  args: array (0)
   |  |  |  line: 16
   |  |  key: null
   |  |  byRef: false
   |  |  line: 16
   |  |  unpack: false
   |  11 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\StaticPropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  line: 17
   |  |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'Test'
   |  |  |  |  |  line: 17
   |  |  |  |  line: 17
   |  |  |  args: array (0)
   |  |  |  line: 17
   |  |  key: null
   |  |  byRef: false
   |  |  line: 17
   |  |  unpack: false
   |  12 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\StaticPropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  line: 18
   |  |  |  |  class: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'test'
   |  |  |  |  |  line: 18
   |  |  |  |  line: 18
   |  |  |  args: array (0)
   |  |  |  line: 18
   |  |  key: null
   |  |  byRef: false
   |  |  line: 18
   |  |  unpack: false
   |  13 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\Expr\StaticPropertyFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\VarLikeIdentifierNode
   |  |  |  |  |  name: 'className'
   |  |  |  |  |  line: 19
   |  |  |  |  class: Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'foo'
   |  |  |  |  |  |  line: 19
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\ArrayAccessNode
   |  |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  |  name: 'weird'
   |  |  |  |  |  |  |  line: 19
   |  |  |  |  |  |  index: Latte\Compiler\Nodes\Php\Scalar\LNumberNode
   |  |  |  |  |  |  |  value: 0
   |  |  |  |  |  |  |  kind: 10
   |  |  |  |  |  |  |  line: 19
   |  |  |  |  |  |  line: 19
   |  |  |  |  |  line: 19
   |  |  |  |  line: 19
   |  |  |  args: array (0)
   |  |  |  line: 19
   |  |  key: null
   |  |  byRef: false
   |  |  line: 19
   |  |  unpack: false
   |  14 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\NewNode
   |  |  |  class: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  parts: array (1)
   |  |  |  |  |  0 => 'A'
   |  |  |  |  line: 22
   |  |  |  args: array (0)
   |  |  |  line: 22
   |  |  key: null
   |  |  byRef: false
   |  |  line: 22
   |  |  unpack: false
   line: null
