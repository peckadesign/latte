<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler;

use Latte\CompileException;
use Latte\RegexpException;
use Latte\Strict;


/**
 * Lexer for PHP-like expression language used in tags.
 */
final class TagLexer
{
	use Strict;

	private const Keywords = [
		'and' => Token::Php_LogicalAnd,
		'array' => Token::Php_Array,
		'clone' => Token::Php_Clone,
		'default' => Token::Php_Default,
		'empty' => Token::Php_Empty,
		'fn' => Token::Php_Fn,
		'function' => Token::Php_Function,
		'in' => Token::Php_In,
		'instanceof' => Token::Php_Instanceof,
		'isset' => Token::Php_Isset,
		'list' => Token::Php_List,
		'match' => Token::Php_Match,
		'new' => Token::Php_New,
		'or' => Token::Php_LogicalOr,
		'return' => Token::Php_Return,
		'use' => Token::Php_Use,
		'xor' => Token::Php_LogicalXor,
	];

	private string $input;
	private array $tokens;
	private int $offset;
	private int $line;
	private int $column;


	public function tokenize(
		string $input,
		int &$line = 1,
		int &$column = 1,
		int &$offset = 0,
		bool $toEnd = true,
	): array {
		$this->input = $input;
		$this->tokens = [];
		$this->offset = &$offset;
		$this->line = &$line;
		$this->column = &$column;

		$this->tokenizeCode();

		if ($toEnd && $this->offset !== strlen($input)) {
			$token = str_replace("\n", '\n', substr($input, $this->offset, 10));
			throw new CompileException("Unexpected '$token'", $this->line, $this->column);
		}

		return $this->tokens;
	}


	private function tokenizeCode(): void
	{
		$re = <<<'XX'
			~(?J)(?n)   # allow duplicate named groups, no auto capture
			(?(DEFINE) (?<label>  [a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*  ) )

			(?<Php_Whitespace>  [ \n\r\t]+  )|
			( (?<Php_ConstantEncapsedString>  '  )  (?<rest>  ( \\. | [^'\\] )*  '  )?  )|
			( (?<string>  "  )  .*  )|
			( (?<Php_StartHeredoc>  <<< [ \t]* (?: (?&label) | ' (?&label) ' | " (?&label) " ) \r?\n  ) .*  )|
			( (?<Php_Comment>  /\*  )   (?<rest>  .*?\*/  )?  )|
			(?<Php_Variable>  \$  (?&label)  )|
			(?<Php_DNumber>
				((?&lnum) | (?&dnum)) [eE][+-]? (?&lnum)|
				(?<dnum>   (?&lnum)? \. (?&lnum) | (?&lnum) \. (?&lnum)?  )
			)|
			(?<Php_LNumber>
				0[xX][0-9a-fA-F]+(_[0-9a-fA-F]+)*|
				0[bB][01]+(_[01]+)*|
				0[oO][0-7]+(_[0-7]+)*|
				(?<lnum>  [0-9]+(_[0-9]+)*  )
			)|
			(?<Php_NameFullyQualified>  \\ (?&label) ( \\ (?&label) )*  )|
			(?<Php_NameQualified>  (?&label) ( \\ (?&label) )+  )|
			(?<Php_ConstantString>  ( [A-Z_][A-Z0-9_]{2,} | true|TRUE | false|FALSE | null|NULL )  \b)|
			(?<Php_Identifier>  (?&label)(--?[a-zA-Z0-9_\x80-\xff]+)*  )|
			(
				(
					(?<Php_ObjectOperator>  ->  )|
					(?<Php_NullsafeObjectOperator>  \?->  )|
					(?<Php_UndefinedsafeObjectOperator>  \?\?->  )
				)
				(?<Php_Whitespace>  [ \n\r\t]+  )?
				(?<Php_Identifier>  (?&label)  )?
			)|
			(?<Php_DoubleArrow>  =>  )|
			(?<Php_PlusEqual>  \+=  )|
			(?<Php_MinusEqual>  -=  )|
			(?<Php_MulEqual>  \*=  )|
			(?<Php_DivEqual>  /=  )|
			(?<Php_ConcatEqual>  \.=  )|
			(?<Php_ModEqual>  %=  )|
			(?<Php_AndEqual>  &=  )|
			(?<Php_OrEqual>  \|=  )|
			(?<Php_XorEqual>  \^=  )|
			(?<Php_SlEqual>  <<=  )|
			(?<Php_SrEqual>  >>=  )|
			(?<Php_PowEqual>  \*\*=  )|
			(?<Php_CoalesceEqual>  \?\?=  )|
			(?<Php_Coalesce>  \?\?  )|
			(?<Php_BooleanOr>  \|\|  )|
			(?<Php_BooleanAnd>  &&  )|
			(?<Php_AmpersandFollowed>  & (?= [ \t\r\n]* (\$|\.\.\.) )  )|
			(?<Php_AmpersandNotFollowed>  &  )|
			(?<Php_IsIdentical>  ===  )|
			(?<Php_IsNotIdentical>  !==  )|
			(?<Php_IsEqual>  ==  )|
			(?<Php_IsNotEqual>  !=  |  <>  )|
			(?<Php_Spaceship>  <=>  )|
			(?<Php_IsSmallerOrEqual>  <=  )|
			(?<Php_IsGreaterOrEqual>  >=  )|
			(?<Php_Sl>  <<  )|
			(?<Php_Sr>  >>  )|
			(?<Php_Inc>  \+\+  )|
			(?<Php_Dec>  --  )|
			(?<Php_Pow>  \*\*  )|
			(?<Php_PaamayimNekudotayim>  ::  )|
			(?<Php_NsSeparator>  \\  )|
			(?<Php_Ellipsis>  \.\.\.  )|
			(?<Php_IntCast>  \( [ \t]* int [ \t]* \)  )|
			(?<Php_FloatCast>  \( [ \t]* float [ \t]* \)  )|
			(?<Php_StringCast>  \( [ \t]* string [ \t]* \)  )|
			(?<Php_ArrayCast>  \( [ \t]* array [ \t]* \)  )|
			(?<Php_ObjectCast>  \( [ \t]* object [ \t]* \)  )|
			(?<Php_BoolCast>  \( [ \t]* bool [ \t]* \)  )|
			(?<Php_ExpandCast>  \( [ \t]* expand [ \t]* \)  )|
			( (?<char>  }  ) .* )|
			(?<char>  [;:,.|^&+/*=%!\~$<>?@#(){[\]-]  )|
			(?<badchar>  .  )
			~xsA
			XX;

		$depth = 0;
		matchRE:
		preg_match_all($re, $this->input, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL, $this->offset);
		if (preg_last_error()) {
			throw new RegexpException;
		}

		foreach ($matches as $m) {
			if (isset($m['char'])) {
				if ($m['char'] === '{') {
					$depth++;
				} elseif ($m['char'] === '}') {
					$depth--;
					if ($depth < 0) {
						return;
					}
					$this->addToken(null, '}');
					goto matchRE;
				}
				$this->addToken(null, $m['char']);

			} elseif (isset($m[$type = 'Php_ObjectOperator'])
				|| isset($m[$type = 'Php_NullsafeObjectOperator'])
				|| isset($m[$type = 'Php_UndefinedsafeObjectOperator'])
			) {
				$this->addToken(constant(Token::class . '::' . $type), $m[$type]);
				if (isset($m['Php_Whitespace'])) {
					$this->addToken(Token::Php_Whitespace, $m['Php_Whitespace']);
				}
				if (isset($m['Php_Identifier'])) {
					$this->addToken(Token::Php_Identifier, $m['Php_Identifier']);
				}

			} elseif (isset($m['Php_Identifier'])) {
				$this->addToken(self::Keywords[strtolower($m['Php_Identifier'])] ?? Token::Php_Identifier, $m['Php_Identifier']);

			} elseif (isset($m['Php_ConstantEncapsedString'])) {
				isset($m['rest'])
					? $this->addToken(Token::Php_ConstantEncapsedString, "'" . $m['rest'])
					: throw new CompileException('Unterminated string.', $this->line, $this->column);

			} elseif (isset($m['string'])) {
				$this->addToken(null, '"');
				$count = count($this->tokens);
				$line = $this->line;
				$this->tokenizeString('"');
				$token = $this->tokens[$count] ?? null;
				if (count($this->tokens) > $count + 1
					|| ($token && $token->type !== Token::Php_EncapsedAndWhitespace)
				) {
					$this->addToken(null, '"');
				} else {
					array_splice($this->tokens, $count - 1, null, [new Token(Token::Php_ConstantEncapsedString, '"' . $token?->text . '"', $line)]);
					$this->offset++;
				}
				goto matchRE;

			} elseif (isset($m['Php_LNumber'])) {
				$num = PhpHelpers::decodeNumber($m['Php_LNumber']);
				$this->addToken(is_float($num) ? Token::Php_DNumber : Token::Php_LNumber, $m['Php_LNumber']);

			} elseif (isset($m['Php_StartHeredoc'])) {
				$this->addToken(Token::Php_StartHeredoc, $m['Php_StartHeredoc']);
				$endRe = '(?<=\n)[ \t]*' . trim($m['Php_StartHeredoc'], "< \t\r\n'\"") . '\b';
				if (str_contains($m['Php_StartHeredoc'], "'")) { // nowdoc
					if (!preg_match('~(.*?)(' . $endRe . ')~sA', $this->input, $m, 0, $this->offset)) {
						throw new CompileException('Unterminated NOWDOC.', $this->line);
					} elseif ($m[1] !== '') {
						$this->addToken(Token::Php_EncapsedAndWhitespace, $m[1]);
					}
					$this->addToken(Token::Php_EndHeredoc, $m[2]);
				} else {
					$end = $this->tokenizeString($endRe);
					$this->addToken(Token::Php_EndHeredoc, $end);
				}
				goto matchRE;

			} elseif (isset($m['Php_Comment'])) {
				isset($m['rest'])
					? $this->addToken(Token::Php_Comment, '/*' . $m['rest'])
					: throw new CompileException('Unterminated comment.', $this->line, $this->column);

			} elseif (isset($m['badchar'])) {
				throw new CompileException("Unexpected '$m[badchar]'", $this->line, $this->column);

			} else {
				foreach ($m as $type => $text) {
					if ($text !== null && !is_int($type)) {
						$this->addToken(constant(Token::class . '::' . $type), $text);
						break;
					}
				}
			}
		}
	}


	private function tokenizeString(string $endRe): string
	{
		$re = <<<'XX'
			~(?J)(?n)   # allow duplicate named groups, no auto capture
			(?(DEFINE) (?<label>  [a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*  ) )

			( (?<Php_CurlyOpen>  \{\$  )  .*  )|
			(?<Php_DollarOpenCurlyBraces>  \$\{  )|
			( (?<Php_Variable>  \$  (?&label)  )
				(
					(
						(?<Php_ObjectOperator>  ->  )|
						(?<Php_NullsafeObjectOperator>  \?->  )|
						(?<Php_UndefinedsafeObjectOperator>  \?\?->  )
					)
					(?<Php_Identifier>  (?&label)  )
					|
					(?<offset>  \[  )
					(
						(?<offsetVar>  \$  (?&label)  )|
						(?<offsetString>  (?&label)  )|
						(?<offsetMinus>  -  )?
						(?<Php_NumString>
							0[xX][0-9a-fA-F]+(_[0-9a-fA-F]+)*|
							0[bB][01]+(_[01]+)*|
							0[oO][0-7]+(_[0-7]+)*|
							[0-9]+(_[0-9]+)*
						)
					)?
					(?<offsetEnd>  ]  )?
					|
				)
			)|
			((?<end>  %end%  )  .*  )|
			(?<char>  ( \\. | [^\\] )  )
			~xsA
			XX;

		$re = str_replace('%end%', $endRe, $re);
		$start = [$this->line, $this->column - 1];
		matchRE:
		preg_match_all($re, $this->input, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL, $this->offset);
		if (preg_last_error()) {
			throw new RegexpException;
		}

		$buffer = '';
		foreach ($matches as $m) {
			if (isset($m['char'])) {
				$buffer .= $m['char'];
				continue;
			} elseif ($buffer !== '') {
				$this->addToken(Token::Php_EncapsedAndWhitespace, $buffer);
				$buffer = '';
			}

			if (isset($m['Php_CurlyOpen'])) {
				$this->addToken(Token::Php_CurlyOpen, '{');
				$this->tokenizeCode();
				if (($this->input[$this->offset] ?? null) === '}') {
					$this->addToken(null, '}');
				}
				goto matchRE;

			} elseif (isset($m['Php_DollarOpenCurlyBraces'])) {
				throw new CompileException('Syntax ${...} is not supported.', $this->line, $this->column);

			} elseif (isset($m['Php_Variable'])) {
				$this->addToken(Token::Php_Variable, $m['Php_Variable']);
				if (isset($m[$type = 'Php_ObjectOperator'])
					|| isset($m[$type = 'Php_NullsafeObjectOperator'])
					|| isset($m[$type = 'Php_UndefinedsafeObjectOperator'])
				) {
					$this->addToken(constant(Token::class . '::' . $type), $m[$type]);
					$this->addToken(Token::Php_Identifier, $m['Php_Identifier']);

				} elseif (isset($m['offset'])) {
					$this->addToken(null, '[');
					if (!isset($m['offsetEnd'])) {
						throw new CompileException("Missing ']'", $this->line, $this->column);
					} elseif (isset($m['offsetVar'])) {
						$this->addToken(Token::Php_Variable, $m['offsetVar']);
					} elseif (isset($m['offsetString'])) {
						$this->addToken(Token::Php_Identifier, $m['offsetString']);
					} elseif (isset($m['Php_NumString'])) {
						if (isset($m['offsetMinus'])) {
							$this->addToken(null, '-');
						}
						$this->addToken(Token::Php_NumString, $m['Php_NumString']);
					} else {
						throw new CompileException("Unexpected '" . substr($this->input, $this->offset - 1, 5) . "'", $this->line, $this->column);
					}
					$this->addToken(null, ']');
				}

			} elseif (isset($m['end'])) {
				return $m['end'];
			}
		}

		throw new CompileException('Unterminated string.', ...$start);
	}


	private function addToken(?int $type, string $text)
	{
		$this->tokens[] = new Token($type ?? ord($text), $text, $this->line, $this->column);
		$this->offset += strlen($text);
		if ($lines = substr_count($text, "\n")) {
			$this->line += $lines;
			$this->column = strlen($text) - strrpos($text, "\n");
		} else {
			$this->column += strlen($text);
		}
	}
}
