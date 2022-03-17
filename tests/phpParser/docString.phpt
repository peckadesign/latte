<?php

// Nowdoc and heredoc strings

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$test = <<<'XX'
	/* empty strings */
	<<<'EOS'
	EOS,
	<<<EOS
	EOS,

	/* constant encapsed strings */
	<<<'EOS'
	Test '" $a \n
	EOS,
	<<<EOS
	Test '" \$a \n
	EOS,

	/* encapsed strings */
	<<<EOS
	Test $a
	EOS,
	<<<EOS
	Test $a and $b->c test
	EOS,
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
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  line: 2
   |  |  key: null
   |  |  byRef: false
   |  |  line: 2
   |  |  unpack: false
   |  1 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: ''
   |  |  |  line: 4
   |  |  key: null
   |  |  byRef: false
   |  |  line: 4
   |  |  unpack: false
   |  2 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Test '" $a \n'
   |  |  |  line: 8
   |  |  key: null
   |  |  byRef: false
   |  |  line: 8
   |  |  unpack: false
   |  3 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\StringNode
   |  |  |  value: 'Test '" $a \n'
   |  |  |  line: 11
   |  |  key: null
   |  |  byRef: false
   |  |  line: 11
   |  |  unpack: false
   |  4 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (2)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: 'Test '
   |  |  |  |  |  line: 17
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 17
   |  |  |  line: 16
   |  |  key: null
   |  |  byRef: false
   |  |  line: 16
   |  |  unpack: false
   |  5 => Latte\Compiler\Nodes\Php\Expr\ArrayItemNode
   |  |  value: Latte\Compiler\Nodes\Php\Scalar\EncapsedStringNode
   |  |  |  parts: array (5)
   |  |  |  |  0 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: 'Test '
   |  |  |  |  |  line: 20
   |  |  |  |  1 => Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  name: 'a'
   |  |  |  |  |  line: 20
   |  |  |  |  2 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' and '
   |  |  |  |  |  line: 20
   |  |  |  |  3 => Latte\Compiler\Nodes\Php\Expr\PropertyFetchNode
   |  |  |  |  |  name: Latte\Compiler\Nodes\Php\IdentifierNode
   |  |  |  |  |  |  name: 'c'
   |  |  |  |  |  |  line: 20
   |  |  |  |  |  var: Latte\Compiler\Nodes\Php\Expr\VariableNode
   |  |  |  |  |  |  name: 'b'
   |  |  |  |  |  |  line: 20
   |  |  |  |  |  line: 20
   |  |  |  |  4 => Latte\Compiler\Nodes\Php\Scalar\EncapsedStringPartNode
   |  |  |  |  |  value: ' test'
   |  |  |  |  |  line: 20
   |  |  |  line: 19
   |  |  key: null
   |  |  byRef: false
   |  |  line: 19
   |  |  unpack: false
   line: null
