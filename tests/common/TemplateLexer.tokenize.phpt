<?php

declare(strict_types=1);

use Latte\Compiler\Token;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function parse($s)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	return array_map(
		fn(Token $token) => [$token->type, $token->text, $token->line . ':' . $token->column],
		iterator_to_array($lexer->tokenize($s), false),
	);
}


Assert::same([
	[Token::Text, '<0>', '1:1'],
], parse('<0>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'x:-._', '1:2'],
	[Token::Html_TagClose, '>', '1:7'],
], parse('<x:-._>'));

Assert::same([
	[Token::Html_BogusOpen, '<?', '1:1'],
	[Token::Text, 'xml encoding="', '1:3'],
	[Token::Latte_TagOpen, '{', '1:17'],
	[Token::Php_Variable, '$enc', '1:18'],
	[Token::Latte_TagClose, '}', '1:22'],
	[Token::Text, '" ?', '1:23'],
	[Token::Html_TagClose, '>', '1:26'],
	[Token::Text, 'text', '1:27'],
], parse('<?xml encoding="{$enc}" ?>text'));

Assert::same([
	[Token::Html_BogusOpen, '<?', '1:1'],
	[Token::Text, 'php $abc ?', '1:3'],
	[Token::Html_TagClose, '>', '1:13'],
	[Token::Text, 'text', '1:14'],
], parse('<?php $abc ?>text'));

Assert::same([
	[Token::Html_BogusOpen, '<?', '1:1'],
	[Token::Text, '= $abc ?', '1:3'],
	[Token::Html_TagClose, '>', '1:11'],
	[Token::Text, 'text', '1:12'],
], parse('<?= $abc ?>text'));

Assert::same([
	[Token::Html_BogusOpen, '<?', '1:1'],
	[Token::Text, 'bogus', '1:3'],
	[Token::Html_TagClose, '>', '1:8'],
	[Token::Text, 'text', '1:9'],
], parse('<?bogus>text'));

Assert::same([
	[Token::Html_BogusOpen, '<!', '1:1'],
	[Token::Text, 'doctype html', '1:3'],
	[Token::Html_TagClose, '>', '1:15'],
	[Token::Text, 'text', '1:16'],
], parse('<!doctype html>text'));

Assert::same([
	[Token::Html_BogusOpen, '<!', '1:1'],
	[Token::Text, '--', '1:3'],
	[Token::Html_TagClose, '>', '1:5'],
	[Token::Text, ' text> --> text', '1:6'],
], parse('<!--> text> --> text'));

Assert::same([
	[Token::Html_CommentOpen, '<!--', '1:1'],
	[Token::Text, ' text> ', '1:5'],
	[Token::Html_CommentClose, '-->', '1:12'],
	[Token::Text, ' text', '1:15'],
], parse('<!-- text> --> text'));

Assert::same([
	[Token::Html_BogusOpen, '<!', '1:1'],
	[Token::Text, 'bogus', '1:3'],
	[Token::Html_TagClose, '>', '1:8'],
	[Token::Text, 'text', '1:9'],
], parse('<!bogus>text'));

// html attributes
Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'div', '1:2'],
	[Token::Whitespace, ' ', '1:5'],
	[Token::Html_Name, 'a', '1:6'],
	[Token::Whitespace, ' ', '1:7'],
	[Token::Html_Name, 'b', '1:8'],
	[Token::Whitespace, ' ', '1:9'],
	[Token::Html_Name, 'c', '1:10'],
	[Token::Whitespace, ' ', '1:11'],
	[Token::Equals, '=', '1:12'],
	[Token::Whitespace, ' ', '1:13'],
	[Token::Html_Name, 'd', '1:14'],
	[Token::Whitespace, ' ', '1:15'],
	[Token::Html_Name, 'e', '1:16'],
	[Token::Whitespace, ' ', '1:17'],
	[Token::Equals, '=', '1:18'],
	[Token::Whitespace, ' ', '1:19'],
	[Token::Quote, '"', '1:20'],
	[Token::Text, 'f', '1:21'],
	[Token::Quote, '"', '1:22'],
	[Token::Whitespace, ' ', '1:23'],
	[Token::Html_Name, 'g', '1:24'],
	[Token::Html_TagClose, '>', '1:25'],
	[Token::Html_TagOpen, '<', '1:26'],
	[Token::Slash, '/', '1:27'],
	[Token::Html_Name, 'div', '1:28'],
	[Token::Html_TagClose, '>', '1:31'],
], parse('<div a b c = d e = "f" g></div>'));

Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'div', '1:2'],
	[Token::Whitespace, ' ', '1:5'],
	[Token::Html_Name, 'a', '1:6'],
	[Token::Whitespace, ' ', '1:7'],
	[Token::Latte_TagOpen, '{', '1:8'],
	[Token::Latte_Name, 'b', '1:9'],
	[Token::Latte_TagClose, '}', '1:10'],
	[Token::Whitespace, ' ', '1:11'],
	[Token::Html_Name, 'c', '1:12'],
	[Token::Whitespace, ' ', '1:13'],
	[Token::Equals, '=', '1:14'],
	[Token::Whitespace, ' ', '1:15'],
	[Token::Latte_TagOpen, '{', '1:16'],
	[Token::Latte_Name, 'd', '1:17'],
	[Token::Latte_TagClose, '}', '1:18'],
	[Token::Whitespace, ' ', '1:19'],
	[Token::Html_Name, 'e', '1:20'],
	[Token::Whitespace, ' ', '1:21'],
	[Token::Equals, '=', '1:22'],
	[Token::Whitespace, ' ', '1:23'],
	[Token::Html_Name, 'a', '1:24'],
	[Token::Latte_TagOpen, '{', '1:25'],
	[Token::Latte_Name, 'b', '1:26'],
	[Token::Latte_TagClose, '}', '1:27'],
	[Token::Html_Name, 'c', '1:28'],
	[Token::Whitespace, ' ', '1:29'],
	[Token::Html_Name, 'f', '1:30'],
	[Token::Whitespace, ' ', '1:31'],
	[Token::Equals, '=', '1:32'],
	[Token::Whitespace, ' ', '1:33'],
	[Token::Quote, '"', '1:34'],
	[Token::Text, 'a', '1:35'],
	[Token::Latte_TagOpen, '{', '1:36'],
	[Token::Latte_Name, 'b', '1:37'],
	[Token::Latte_TagClose, '}', '1:38'],
	[Token::Text, 'c', '1:39'],
	[Token::Quote, '"', '1:40'],
	[Token::Html_TagClose, '>', '1:41'],
	[Token::Html_TagOpen, '<', '1:42'],
	[Token::Slash, '/', '1:43'],
	[Token::Html_Name, 'div', '1:44'],
	[Token::Html_TagClose, '>', '1:47'],
], parse('<div a {b} c = {d} e = a{b}c f = "a{b}c"></div>'));

// macro attributes
Assert::same([
	[Token::Html_TagOpen, '<', '1:1'],
	[Token::Html_Name, 'div', '1:2'],
	[Token::Whitespace, ' ', '1:5'],
	[Token::Html_Name, 'n:a', '1:6'],
	[Token::Whitespace, ' ', '1:9'],
	[Token::Html_Name, 'n:b', '1:10'],
	[Token::Whitespace, ' ', '1:13'],
	[Token::Html_Name, 'n:c', '1:14'],
	[Token::Whitespace, ' ', '1:17'],
	[Token::Equals, '=', '1:18'],
	[Token::Whitespace, ' ', '1:19'],
	[Token::Html_Name, 'd', '1:20'],
	[Token::Whitespace, ' ', '1:21'],
	[Token::Html_Name, 'n:e', '1:22'],
	[Token::Whitespace, ' ', '1:25'],
	[Token::Equals, '=', '1:26'],
	[Token::Whitespace, ' ', '1:27'],
	[Token::Quote, '"', '1:28'],
	[Token::Text, 'f', '1:29'],
	[Token::Quote, '"', '1:30'],
	[Token::Whitespace, ' ', '1:31'],
	[Token::Html_Name, 'n:g', '1:32'],
	[Token::Html_TagClose, '>', '1:35'],
	[Token::Html_TagOpen, '<', '1:36'],
	[Token::Slash, '/', '1:37'],
	[Token::Html_Name, 'div', '1:38'],
	[Token::Html_TagClose, '>', '1:41'],
], parse('<div n:a n:b n:c = d n:e = "f" n:g></div>'));
