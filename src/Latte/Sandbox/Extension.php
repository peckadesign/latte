<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\Php\Expr;
use Latte\Compiler\Nodes\Php\NameNode;
use Latte\Compiler\NodeTraverser;
use Latte\SecurityViolationException;


/**
 * Security protection for the sandbox.
 */
final class Extension extends Latte\Extension
{
	private ?Latte\Policy $policy;


	public function beforeCompile(Latte\Engine $engine): void
	{
		$this->policy = $engine->getPolicy(effective: true);
	}


	public function getTags(): array
	{
		return [
			'sandbox' => [Nodes\SandboxNode::class, 'create'],
		];
	}


	public function getPasses(): array
	{
		return $this->policy
			? [-10 => [$this, 'processPass']]
			: [];
	}


	public function beforeRender(Latte\Engine $engine): void
	{
		if ($policy = $engine->getPolicy()) {
			$engine->addProvider('sandbox', new RuntimeChecker($policy));
		}
	}


	public function processPass(Node $node): Node
	{
		return (new NodeTraverser)
			->traverse($node, leave: \Closure::fromCallable([$this, 'sandboxVisitor']));
	}


	private function sandboxVisitor(Node $node): Node
	{
		if ($node instanceof Expr\VariableNode) {
			if ($node->name === 'this') {
				throw new SecurityViolationException("Forbidden variable \${$node->name}.");
			} elseif (!is_string($node->name)) {
				throw new SecurityViolationException('Forbidden variable variables.');
			}
			return $node;

		} elseif ($node instanceof Expr\NewNode) {
			throw new SecurityViolationException("Forbidden keyword 'new'");

		} elseif ($node instanceof Expr\FuncCallNode && $node->name instanceof NameNode) {
			if (!$this->policy->isFunctionAllowed((string) $node->name)) {
				throw new SecurityViolationException("Function $node->name() is not allowed.");
			}
			return $node;

		} elseif ($node instanceof Expr\FilterNode) {
			$name = (string) $node->name;
			if ($name !== $node::Escape && !$this->policy->isFilterAllowed($name)) {
				throw new SecurityViolationException("Filter |$name is not allowed.");
			}
			return $node;

		} elseif ($node instanceof Expr\PropertyFetchNode
			|| $node instanceof Expr\StaticPropertyFetchNode
			|| $node instanceof Expr\NullsafePropertyFetchNode
			|| $node instanceof Expr\UndefinedsafePropertyFetchNode
			|| $node instanceof Expr\FuncCallNode
			|| $node instanceof Expr\MethodCallNode
			|| $node instanceof Expr\StaticCallNode
			|| $node instanceof Expr\NullsafeMethodCallNode
			|| $node instanceof Expr\UndefinedsafeMethodCallNode
		) {
			$class = namespace\Nodes::class . strrchr($node::class, '\\');
			return new $class($node);

		} else {
			return $node;
		}
	}
}
