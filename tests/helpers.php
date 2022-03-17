<?php

declare(strict_types=1);

use Latte\Compiler\Node;
use Latte\Compiler\Nodes;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PrintContext;
use Latte\Compiler\TagLexer;
use Latte\Compiler\Token;
use Tracy\Dumper;


function getTempDir(): string
{
	$dir = __DIR__ . '/tmp/' . getmypid();

	if (empty($GLOBALS['\\lock'])) {
		// garbage collector
		$GLOBALS['\\lock'] = $lock = fopen(__DIR__ . '/lock', 'w');
		if (rand(0, 100)) {
			flock($lock, LOCK_SH);
			@mkdir(dirname($dir));
		} elseif (flock($lock, LOCK_EX)) {
			Tester\Helpers::purge(dirname($dir));
		}

		@mkdir($dir);
	}

	return $dir;
}


function test(string $title, Closure $function): void
{
	$function();
}


function normalizeNl(string $s): string
{
	return str_replace("\r\n", "\n", $s);
}


function parseCode(string $code): Nodes\Php\Expr\ArrayNode
{
	$code = normalizeNl($code);
	$tokens = (new TagLexer)->tokenize($code);
	$parser = new Latte\Compiler\TagParser($tokens);
	$node = $parser->parseArguments();
	if (!$parser->isEnd()) {
		$parser->stream->throwUnexpectedException();
	}
	return $node;
}


function exportNode(Node $node): string
{
	$dump = Dumper::toText($node, [Dumper::HASH => false, Dumper::DEPTH => 20]);
	return trim($dump) . "\n";
}


function printNode(Nodes\Php\Expr\ArrayNode $node): string
{
	$context = new PrintContext;
	$code = $context->implode($node->items, ",\n");
	return $code . "\n";
}


function tokenize(string $code): array
{
	$lexer = new Latte\Compiler\TagLexer;
	return $lexer->tokenize($code);
}


function exportTokens(array $tokens): string
{
	static $table;
	if (!$table) {
		$table = @array_flip((new ReflectionClass(Token::class))->getConstants());
	}
	$res = '';
	foreach ($tokens as $token) {
		$res .= str_pad('#' . $token->line . ':' . $token->column, 6) . ' ';
		if ($token->type > 255) {
			$res .= str_pad($table[$token->type] ?? 'UNKNOWN', 15) . ' ';
		}
		$res .= "'" . addcslashes(normalizeNl($token->text), "\n\t\f\v\"\\") . "'\n";
	}

	return $res;
}


function loadContent(string $file, int $offset): string
{
	$s = file_get_contents($file);
	$s = substr($s, $offset);
	$s = normalizeNl(ltrim($s));
	return $s;
}


class DumpExtension extends Latte\Extension
{
	public Node $node;


	public function getPasses(): array
	{
		return [
			fn(Node $node) => $this->node = $node,
		];
	}


	public function export(?Node $node = null)
	{
		$node ??= $this->node;
		$prop = match (true) {
			$node instanceof Nodes\TextNode => 'content: ' . var_export($node->content, true),
			$node instanceof Nodes\Html\AttributeNode,
				$node instanceof Nodes\Html\ElementNode,
				$node instanceof Nodes\Php\IdentifierNode => 'name: ' . $node->name,
			$node instanceof Nodes\Php\NameNode => 'parts: ' . PhpHelpers::dump($node->parts),
			$node instanceof Nodes\Php\Scalar\DNumberNode,
				$node instanceof Nodes\Php\Scalar\EncapsedStringPartNode,
				$node instanceof Nodes\Php\Scalar\LNumberNode,
				$node instanceof Nodes\Php\Scalar\StringNode => 'value: ' . $node->value,
			$node instanceof Nodes\Php\Expr\AssignOpNode,
				$node instanceof Nodes\Php\Expr\BinaryOpNode => 'operator: ' . $node->operator,
			$node instanceof Nodes\Php\Expr\CastNode => 'type: ' . $node->type,
			$node instanceof Nodes\Php\Expr\VariableNode && is_string($node->name) => 'name: ' . $node->name,
			default => '',
		};
		$res = $prop ? $prop . "\n" : '';
		foreach ($node as $sub) {
			$res .= rtrim($this->export($sub), "\n") . "\n";
		}

		return substr($node::class, strrpos($node::class, '\\') + 1, -4)
			. ':'
			. ($res ? "\n" . preg_replace('#^(?=.)#m', "\t", $res) : '')
			. "\n";
	}
}


function exportTraversing(string $template): string
{
	$latte = new Latte\Engine;
	$latte->setLoader(new Latte\Loaders\StringLoader);
	$latte->addExtension($extension = new DumpExtension);
	$latte->compile($template);
	return $extension->export();
}
