<?php

// Type hints

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	function (
		$a,
		array $b,
		callable $c,
		E $d,
	    ?Foo $e,
	    A|iterable|null $f,
	    A&B $g,
	): never { return null; }
	XX;

$node = parseCode($test);

Assert::same(
	loadContent(__FILE__, __COMPILER_HALT_OFFSET__),
	exportNode($node),
);

__halt_compiler();
Latte\Compiler\Nodes\Php\Expr\ArrayNode
   items: array (1)
   |  0 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Expr\ClosureNode
   |  |  |  byRef: false
   |  |  |  params: array (7)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: null
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'a'
   |  |  |  |  |  |  line: 2
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 2
   |  |  |  |  |  flags: 0
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'array'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 3
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 3
   |  |  |  |  |  flags: 0
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'callable'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  line: 4
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 4
   |  |  |  |  |  flags: 0
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  0 => 'E'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'd'
   |  |  |  |  |  |  line: 5
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 5
   |  |  |  |  |  flags: 0
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NullableTypeNode
   |  |  |  |  |  |  type: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  0 => 'Foo'
   |  |  |  |  |  |  |  line: 6
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'e'
   |  |  |  |  |  |  line: 6
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 6
   |  |  |  |  |  flags: 0
   |  |  |  |  5 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\UnionTypeNode
   |  |  |  |  |  |  types: array (3)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'iterable'
   |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  |  2 => Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  |  |  name: 'null'
   |  |  |  |  |  |  |  |  line: 7
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'f'
   |  |  |  |  |  |  line: 7
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 7
   |  |  |  |  |  flags: 0
   |  |  |  |  6 => Latte\Compiler\Nodes\Php\ParamNode
   |  |  |  |  |  type: Latte\Compiler\Nodes\Php\IntersectionTypeNode
   |  |  |  |  |  |  types: array (2)
   |  |  |  |  |  |  |  0 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'A'
   |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  |  1 => Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  |  |  |  0 => 'B'
   |  |  |  |  |  |  |  |  line: 8
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'g'
   |  |  |  |  |  |  line: 8
   |  |  |  |  |  default: null
   |  |  |  |  |  byRef: false
   |  |  |  |  |  variadic: false
   |  |  |  |  |  line: 8
   |  |  |  |  |  flags: 0
   |  |  |  uses: array (0)
   |  |  |  returnType: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  name: 'never'
   |  |  |  |  line: 9
   |  |  |  expr: Latte\Compiler\Nodes\Php\Expr\ConstFetchNode
   |  |  |  |  name: Latte\Compiler\Nodes\Php\NameNode
   |  |  |  |  |  parts: array (1)
   |  |  |  |  |  |  0 => 'null'
   |  |  |  |  |  line: 9
   |  |  |  |  line: 9
   |  |  |  line: 1
   |  |  key: null
   |  |  byRef: false
   |  |  line: 1
   |  |  unpack: false
   line: null
