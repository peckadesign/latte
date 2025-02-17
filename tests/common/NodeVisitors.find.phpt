<?php

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\NodeVisitors;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/nodehelpers.php';

$leafNode1 = new LeafNode;
$leafNode2 = new LeafNode;
$parentNode = new ParentNode($leafNode2);
$arrayNode = new ArrayNode([$leafNode1, $parentNode]);

$visitors = new NodeVisitors;

$res = $visitors->find($arrayNode, fn(Node $node) => $node instanceof LeafNode);
Assert::same([$leafNode1, $leafNode2], $res);

$res = $visitors->findFirst($arrayNode, fn(Node $node) => $node instanceof LeafNode);
Assert::same($leafNode1, $res);
