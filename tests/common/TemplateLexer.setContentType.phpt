<?php

declare(strict_types=1);

use Latte\Compiler\LegacyToken;
use Latte\Context;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function parse($s, $contentType = null)
{
	$lexer = new Latte\Compiler\TemplateLexer;
	$tokens = $lexer->tokenize($s, $contentType ?: Context::Html);
	return array_map(
		fn(LegacyToken $token) => [$token->type, $token->text],
		iterator_to_array($tokens, false),
	);
}


Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('<script> <div /> </script>', Context::Html));

Assert::same([
	['macroTag', '{contentType html}'],
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' <div /> '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('{contentType html}<script> <div /> </script>'));

Assert::same([
	['htmlTagBegin', '<script'],
	['htmlTagEnd', '>'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['text', ' '],
	['htmlTagEnd', '/>'],
	['text', ' '],
	['htmlTagBegin', '</script'],
	['htmlTagEnd', '>'],
], parse('<script> <div /> </script>', Context::Xml));

Assert::same([
	['text', '<script> <div /> </script>'],
], parse('<script> <div /> </script>', Context::Text));

Assert::same([
	['text', '<script> <div /> </script>'],
], parse('<script> <div /> </script>', Context::ICal));

Assert::same([
	['htmlTagBegin', '<script'],
	['text', ' '],
	['htmlTagEnd', '/>'],
	['text', ' '],
	['htmlTagBegin', '<div'],
	['text', ' '],
	['htmlTagEnd', '/>'],
], parse('<script /> <div />', Context::Html));
