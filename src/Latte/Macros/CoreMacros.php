<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\Tag;
use Latte\Engine;
use Latte\Helpers;


/**
 * Basic macros for Latte.
 */
class CoreMacros extends MacroSet
{
	/** @var array<string, int[]> */
	private array $overwrittenVars;
	private ?string $printTemplate = null;
	private int $idCounter = 0;


	public static function install(Latte\Compiler\Compiler $compiler): void
	{
		$me = new static($compiler);

		$me->addMacro('if', [$me, 'macroIf'], [$me, 'macroEndIf']);
		$me->addMacro('else', [$me, 'macroElse']);
		$me->addMacro('elseif', [$me, 'macroElseIf']);
		$me->addMacro('ifset', 'if (isset(%node.args)) %node.line {', '}');
		$me->addMacro('elseifset', [$me, 'macroElseIf']);
		$me->addMacro('ifcontent', [$me, 'macroIfContent'], [$me, 'macroEndIfContent']);
		$me->addMacro('ifchanged', [$me, 'macroIfChanged'], '}');

		$me->addMacro('switch', '$ʟ_switch = (%node.args) %node.line; if (false) {', '}');
		$me->addMacro('case', [$me, 'macroCase']);

		$me->addMacro('foreach', '', [$me, 'macroEndForeach']);
		$me->addMacro('iterateWhile', [$me, 'macroIterateWhile'], [$me, 'macroEndIterateWhile']);
		$me->addMacro('for', 'for (%node.args) %node.line {', '}');
		$me->addMacro('while', [$me, 'macroWhile'], [$me, 'macroEndWhile']);
		$me->addMacro('continueIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('breakIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('skipIf', [$me, 'macroBreakContinueIf']);
		$me->addMacro('first', 'if ($iterator->isFirst(%node.args)) %node.line {', '}');
		$me->addMacro('last', 'if ($iterator->isLast(%node.args)) %node.line {', '}');
		$me->addMacro('sep', 'if (!$iterator->isLast(%node.args)) %node.line {', '}');

		$me->addMacro('try', [$me, 'macroTry'], '}');
		$me->addMacro('rollback', [$me, 'macroRollback']);

		$me->addMacro('var', [$me, 'macroVar']);
		$me->addMacro('default', [$me, 'macroVar']);
		$me->addMacro('dump', [$me, 'macroDump']);
		$me->addMacro('debugbreak', [$me, 'macroDebugbreak']);
		$me->addMacro('trace', 'LR\Tracer::throw() %node.line;');
		$me->addMacro('l', '?>{<?php');
		$me->addMacro('r', '?>}<?php');

		$me->addMacro('_', [$me, 'macroTranslate'], [$me, 'macroTranslate']);
		$me->addMacro('=', [$me, 'macroExpr']);

		$me->addMacro('capture', [$me, 'macroCapture'], [$me, 'macroCaptureEnd']);
		$me->addMacro('spaceless', [$me, 'macroSpaceless'], [$me, 'macroSpaceless']);
		$me->addMacro('include', [$me, 'macroInclude']);
		$me->addMacro('sandbox', [$me, 'macroSandbox']);
		$me->addMacro('contentType', [$me, 'macroContentType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('php', [$me, 'macroExpr']);
		$me->addMacro('do', [$me, 'macroExpr']);

		$me->addMacro('class', null, null, [$me, 'macroClass']);
		$me->addMacro('attr', null, null, [$me, 'macroAttr']);
		$me->addMacro('tag', [$me, 'macroTag'], [$me, 'macroTagEnd']);

		$me->addMacro('parameters', [$me, 'macroParameters'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('varType', [$me, 'macroVarType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('varPrint', [$me, 'macroVarPrint'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('templateType', [$me, 'macroTemplateType'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('templatePrint', [$me, 'macroTemplatePrint'], null, null, self::ALLOWED_IN_HEAD);
	}


	/**
	 * Initializes before template parsing.
	 * @return void
	 */
	public function initialize()
	{
		$this->overwrittenVars = [];
		$this->idCounter = 0;
	}


	/**
	 * Finishes template parsing.
	 */
	public function finalize()
	{
		if ($this->printTemplate) {
			return ["(new Latte\\Runtime\\Blueprint)->printClass(\$this, {$this->printTemplate}); exit;"];
		}

		$code = '';
		if ($this->overwrittenVars) {
			$vars = array_map(fn($l) => implode(', ', $l), $this->overwrittenVars);
			$code .= 'foreach (array_intersect_key(' . Latte\Compiler\PhpHelpers::dump($vars) . ', $this->params) as $ʟ_v => $ʟ_l) { '
				. 'trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l"); } ';
		}

		$code = $code
			? 'if (!$this->getReferringTemplate() || $this->getReferenceType() === "extends") { ' . $code . '}'
			: '';
		return [$code];
	}


	/********************* macros ****************d*g**/


	/**
	 * {if ...}
	 */
	public function macroIf(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		if ($tag->data->capture = ($tag->args === '')) {
			return $writer->write("ob_start(fn() => '') %node.line; try {");
		}

		if ($tag->prefix === $tag::PREFIX_TAG) {
			for ($id = 0, $tmp = $tag->htmlNode; $tmp = $tmp->parentNode; $id++);
			$tag->htmlNode->data->id ??= $id;
			return $writer->write(
				$tag->htmlNode->closing
					? 'if ($ʟ_if[%var]) %node.line {'
					: 'if ($ʟ_if[%var] = (%node.args)) %node.line {',
				$tag->htmlNode->data->id,
			);
		}

		return $writer->write('if (%node.args) %node.line {');
	}


	/**
	 * {/if ...}
	 */
	public function macroEndIf(Tag $tag, PhpWriter $writer): string
	{
		if (!$tag->data->capture) {
			return '}';
		}

		$tag->validate('condition');

		if (isset($tag->data->else)) {
			return $writer->write('
					} finally {
						$ʟ_ifB = ob_get_clean();
					}
				} finally {
					$ʟ_ifA = ob_get_clean();
				}
				echo (%node.args) ? $ʟ_ifA : $ʟ_ifB %node.line;
			');
		}

		return $writer->write('
			} finally {
				$ʟ_ifA = ob_get_clean();
			}
			if (%node.args) %node.line { echo $ʟ_ifA; }
		');
	}


	/**
	 * {else}
	 */
	public function macroElse(Tag $tag, PhpWriter $writer): string
	{
		if ($tag->args !== '' && str_starts_with($tag->args, 'if')) {
			throw new CompileException('Arguments are not allowed in {else}, did you mean {elseif}?');
		}

		$tag->validate(false, ['if', 'ifset', 'foreach', 'ifchanged', 'try', 'first', 'last', 'sep']);

		$parent = $tag->parentNode;
		if (isset($parent->data->else)) {
			throw new CompileException('Tag ' . $parent->getNotation() . ' may only contain one {else} clause.');
		}

		$parent->data->else = true;
		if ($parent->name === 'if' && $parent->data->capture) {
			return $writer->write("ob_start(fn() => '') %node.line; try {");

		} elseif ($parent->name === 'foreach') {
			return $writer->write('$iterations++; } if ($iterator->isEmpty()) %node.line {');

		} elseif ($parent->name === 'ifchanged' && $parent->data->capture) {
			$res = '?>' . $parent->closingCode . $writer->write('<?php else %node.line {');
			$parent->closingCode = '<?php } ?>';
			return $res;

		} elseif ($parent->name === 'try') {
			$tag->openingCode = $parent->data->codeCatch;
			$parent->closingCode = $parent->data->codeFinally;
			return '';
		}

		return $writer->write('} else %node.line {');
	}


	/**
	 * {elseif}
	 * {elseifset}
	 */
	public function macroElseIf(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(true, ['if', 'ifset']);
		if (isset($tag->parentNode->data->else) || !empty($tag->parentNode->data->capture)) {
			throw new CompileException('Tag ' . $tag->getNotation() . ' is unexpected here.');
		}

		return $writer->write($tag->name === 'elseif'
			? '} elseif (%node.args) %node.line {'
			: '} elseif (isset(%node.args)) %node.line {');
	}


	/**
	 * n:ifcontent
	 */
	public function macroIfContent(Tag $tag, PhpWriter $writer): void
	{
		if (!$tag->prefix || $tag->prefix !== Tag::PREFIX_NONE) {
			throw new CompileException("Unknown {$tag->getNotation()}, use n:{$tag->name} attribute.");
		}
		if ($tag->htmlNode->empty) {
			throw new CompileException("Unnecessary n:content on empty element <{$tag->htmlNode->name}>");
		}

		$tag->validate(false);
	}


	/**
	 * n:ifcontent
	 */
	public function macroEndIfContent(Tag $tag, PhpWriter $writer): void
	{
		$id = ++$this->idCounter;
		$tag->openingCode = "<?php ob_start(fn() => ''); try { ?>";
		$tag->innerContent = '<?php ob_start(); try { ?>'
			. $tag->innerContent
			. "<?php } finally { \$ʟ_ifc[$id] = rtrim(ob_get_flush()) === ''; } ?>";
		$tag->closingCode = "<?php } finally { if (\$ʟ_ifc[$id] ?? null) { ob_end_clean(); } else { echo ob_get_clean(); } } ?>";
	}


	/**
	 * {ifchanged [...]}
	 */
	public function macroIfChanged(Tag $tag, PhpWriter $writer): void
	{
		$tag->validate(null);
		$id = $tag->data->id = ++$this->idCounter;
		if ($tag->data->capture = ($tag->args === '')) {
			$tag->openingCode = $writer->write("<?php ob_start(fn() => ''); try %node.line { ?>");
			$tag->closingCode =
				'<?php } finally { $ʟ_tmp = ob_get_clean(); } '
				. "if ((\$ʟ_loc[$id] ?? null) !== \$ʟ_tmp) { echo \$ʟ_loc[$id] = \$ʟ_tmp; } ?>";
		} else {
			$tag->openingCode = $writer->write(
				'<?php if (($ʟ_loc[%0_var] ?? null) !== ($ʟ_tmp = [%node.args])) { $ʟ_loc[%0_var] = $ʟ_tmp; ?>',
				$id,
			);
		}
	}


	/**
	 * {try}
	 */
	public function macroTry(Tag $tag, PhpWriter $writer): void
	{
		$tag->replaced = false;
		$tag->validate(false);
		for ($id = 0, $tmp = $tag; $tmp = $tmp->closest(['try']); $id++);
		$tag->data->codeCatch = '<?php
			} catch (Throwable $ʟ_e) {
				ob_end_clean();
				if (!($ʟ_e instanceof LR\RollbackException) && isset($this->global->coreExceptionHandler)) {
					($this->global->coreExceptionHandler)($ʟ_e, $this);
				}
			?>';
		$tag->data->codeFinally = $writer->write('<?php
				ob_start();
			} finally {
				echo ob_get_clean();
				$iterator = $ʟ_it = $ʟ_try[%0_var][0];
			} ?>', $id);
		$tag->openingCode = $writer->write('<?php $ʟ_try[%var] = [$ʟ_it ?? null]; ob_start(fn() => \'\'); try %node.line { ?>', $id);
		$tag->closingCode = $tag->data->codeCatch . $tag->data->codeFinally;
	}


	/**
	 * {rollback}
	 */
	public function macroRollback(Tag $tag, PhpWriter $writer): string
	{
		$parent = $tag->closest(['try']);
		if (!$parent || isset($parent->data->catch)) {
			throw new CompileException('Tag {rollback} must be inside {try} ... {/try}.');
		}

		$tag->validate(false);

		return $writer->write('throw new LR\RollbackException;');
	}


	/**
	 * {_$var |modifiers}
	 */
	public function macroTranslate(Tag $tag, PhpWriter $writer): string
	{
		if ($tag->closing) {
			if (!str_contains($tag->content, '<?php')) {
				$tmp = $tag->content;
				$tag->content = '';
				return $writer->write(
					'$ʟ_fi = new LR\FilterInfo(%var);
					echo %modifyContent($this->filters->filterContent("translate", $ʟ_fi, %raw)) %node.line;',
					implode('', $tag->context),
					PhpHelpers::dump($tmp),
				);
			}

			$tag->openingCode = "<?php ob_start(fn() => ''); try { ?>" . $tag->openingCode;
			return $writer->write(
				'} finally {
					$ʟ_tmp = ob_get_clean();
				}
				$ʟ_fi = new LR\FilterInfo(%var);
				echo %modifyContent($this->filters->filterContent("translate", $ʟ_fi, $ʟ_tmp)) %node.line;',
				implode('', $tag->context),
			);

		} elseif ($tag->empty = ($tag->args !== '')) {
			return $writer->write('echo %modify(($this->filters->translate)(%node.args)) %node.line;');
		}

		return '';
	}


	/**
	 * {include [file] "file" [with blocks] [,] [params]}
	 */
	public function macroInclude(Tag $tag, PhpWriter $writer): string
	{
		[$file,] = $tag->tokenizer->fetchWordWithModifier('file');
		$mode = 'include';
		if ($tag->tokenizer->isNext('with') && !$tag->tokenizer->isPrev(',')) {
			$tag->tokenizer->consumeValue('with');
			$tag->tokenizer->consumeValue('blocks');
			$mode = 'includeblock';
		}

		$tag->replaced = false;
		$noEscape = Helpers::removeFilter($tag->modifiers, 'noescape');
		if ($tag->modifiers && !$noEscape) {
			$tag->modifiers .= '|escape';
		}

		return $writer->write(
			'$this->createTemplate(%word, %node.array? + $this->params, %var)->renderToContentType(%raw) %node.line;',
			$file,
			$mode,
			$tag->modifiers
				? $writer->write('function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }')
				: PhpHelpers::dump($noEscape ? null : implode('', $tag->context)),
		);
	}


	/**
	 * {sandbox "file" [,] [params]}
	 */
	public function macroSandbox(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		$tag->replaced = false;
		return $writer->write(
			'ob_start(fn() => \'\');
			try { $this->createTemplate(%node.word, %node.array, "sandbox")->renderToContentType(%var) %node.line; echo ob_get_clean(); }
			catch (\Throwable $ʟ_e) {
				if (isset($this->global->coreExceptionHandler)) { ob_end_clean(); ($this->global->coreExceptionHandler)($ʟ_e, $this); }
				else { echo ob_get_clean(); throw $ʟ_e; }
			}',
			implode('', $tag->context),
		);
	}


	/**
	 * {capture $variable}
	 */
	public function macroCapture(Tag $tag, PhpWriter $writer): string
	{
		$variable = $tag->tokenizer->fetchWord();
		if (!$variable) {
			throw new CompileException('Missing variable in {capture}.');
		} elseif (!str_starts_with($variable, '$')) {
			throw new CompileException("Invalid capture block variable '$variable'");
		}

		$this->checkExtraArgs($tag);
		$tag->data->variable = $variable;
		return $writer->write("ob_start(fn() => '') %node.line; try {");
	}


	/**
	 * {/capture}
	 */
	public function macroCaptureEnd(Tag $tag, PhpWriter $writer): string
	{
		$body = implode('', $tag->context) === Engine::CONTENT_HTML
			? 'ob_get_length() ? new LR\\Html(ob_get_clean()) : ob_get_clean()'
			: 'ob_get_clean()';
		return $writer->write(
			'} finally {
				$ʟ_tmp = %raw;
			}
			$ʟ_fi = new LR\FilterInfo(%var); %raw = %modifyContent($ʟ_tmp);',
			$body,
			implode('', $tag->context),
			$tag->data->variable,
		);
	}


	/**
	 * {spaceless} ... {/spaceless}
	 */
	public function macroSpaceless(Tag $tag, PhpWriter $writer): void
	{
		$tag->validate(false);
		$tag->openingCode = $writer->write($tag->context[0] === Engine::CONTENT_HTML
			? "<?php ob_start('Latte\\Runtime\\Filters::spacelessHtmlHandler', 4096) %node.line; try { ?>"
			: "<?php ob_start('Latte\\Runtime\\Filters::spacelessText', 4096) %node.line; try { ?>");
		$tag->closingCode = '<?php } finally { ob_end_flush(); } ?>';
	}


	/**
	 * {while ...}
	 */
	public function macroWhile(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		if ($tag->data->do = ($tag->args === '')) {
			return $writer->write('do %node.line {');
		}

		return $writer->write('while (%node.args) %node.line {');
	}


	/**
	 * {/while ...}
	 */
	public function macroEndWhile(Tag $tag, PhpWriter $writer): string
	{
		if ($tag->data->do) {
			$tag->validate(true);
			return $writer->write('} while (%node.args);');
		}

		return '}';
	}


	/**
	 * {foreach ...}
	 */
	public function macroEndForeach(Tag $tag, PhpWriter $writer): void
	{
		$noCheck = Helpers::removeFilter($tag->modifiers, 'nocheck');
		$noIterator = Helpers::removeFilter($tag->modifiers, 'noiterator');
		if ($tag->modifiers) {
			throw new CompileException('Only modifiers |noiterator and |nocheck are allowed here.');
		}

		$tag->validate(true);
		$tag->openingCode = '<?php $iterations = 0; ';
		$args = $writer->formatArgs();
		if (!$noCheck) {
			preg_match('#.+\s+as\s*\$(\w+)(?:\s*=>\s*\$(\w+))?#i', $args, $m);
			for ($i = 1; $i < count($m); $i++) {
				$this->overwrittenVars[$m[$i]][] = $tag->startLine;
			}
		}

		if (
			!$noIterator
			&& preg_match('#\$iterator\W|\Wget_defined_vars\W#', $this->getCompiler()->expandTokens($tag->content))
		) {
			$args = preg_replace('#(.*)\s+as\s+#i', '$1, $ʟ_it ?? null) as ', $args, 1);
			$tag->openingCode .= $writer->write('foreach ($iterator = $ʟ_it = new LR\CachingIterator(%raw) %node.line { ?>', $args);
			$tag->closingCode = '<?php $iterations++; } $iterator = $ʟ_it = $ʟ_it->getParent(); ?>';
		} else {
			$tag->openingCode .= $writer->write('foreach (%raw) %node.line { ?>', $args);
			$tag->closingCode = '<?php $iterations++; } ?>';
		}
	}


	/**
	 * {iterateWhile ...}
	 */
	public function macroIterateWhile(Tag $tag, PhpWriter $writer): void
	{
		if (!$tag->closest(['foreach'])) {
			throw new CompileException('Tag ' . $tag->getNotation() . ' must be inside {foreach} ... {/foreach}.');
		}

		$tag->data->begin = $tag->args !== '';
	}


	/**
	 * {/iterateWhile ...}
	 */
	public function macroEndIterateWhile(Tag $tag, PhpWriter $writer): void
	{
		$tag->validate(true);
		$foreach = $tag->closest(['foreach']);
		$vars = preg_replace('#^.+\s+as\s+(?:(.+)=>)?(.+)$#i', '$1, $2', $foreach->args);
		$stmt = '
		 	if (!$iterator->hasNext()' . ($tag->args ? $writer->write(' || !(%node.args)') : '') . ') {
		 		break;
		 	}
		 	$iterator->next();
		 	[' . $vars . '] = [$iterator->key(), $iterator->current()];
		';
		if ($tag->data->begin) {
			$tag->openingCode = $writer->write('<?php do %node.line { %raw ?>', $stmt);
			$tag->closingCode = '<?php } while (true); ?>';
		} else {
			$tag->openingCode = $writer->write('<?php do %node.line { ?>');
			$tag->closingCode = "<?php $stmt } while (true); ?>";
		}
	}


	/**
	 * {breakIf ...}
	 * {continueIf ...}
	 * {skipIf ...}
	 */
	public function macroBreakContinueIf(Tag $tag, PhpWriter $writer): string
	{
		if ($tag->name === 'skipIf') {
			$ancestors = ['foreach'];
			$cmd = '{ $iterator->skipRound(); continue; }';
		} else {
			$ancestors = ['for', 'foreach', 'while'];
			$cmd = str_replace('If', '', $tag->name);
		}

		if (!$tag->closest($ancestors)) {
			throw new CompileException('Tag ' . $tag->getNotation() . ' is unexpected here.');
		}

		$tag->validate('condition');

		if ($tag->parentNode->prefix === $tag::PREFIX_NONE) {
			return $writer->write("if (%node.args) %node.line { echo \"</{$tag->parentNode->htmlNode->name}>\\n\"; $cmd; }");
		}

		return $writer->write("if (%node.args) %node.line $cmd;");
	}


	/**
	 * n:class="..."
	 */
	public function macroClass(Tag $tag, PhpWriter $writer): string
	{
		if (isset($tag->htmlNode->attrs['class'])) {
			throw new CompileException('It is not possible to combine class with n:class.');
		}

		$tag->validate(true);
		return $writer->write('echo ($ʟ_tmp = array_filter(%node.array)) ? \' class="\' . %escape(implode(" ", array_unique($ʟ_tmp))) . \'"\' : "" %node.line;');
	}


	/**
	 * n:attr="..."
	 */
	public function macroAttr(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(true);
		return $writer->write('$ʟ_tmp = %node.array; echo LR\Filters::htmlAttributes(isset($ʟ_tmp[0]) && is_array($ʟ_tmp[0]) ? $ʟ_tmp[0] : $ʟ_tmp) %node.line;');
	}


	/**
	 * n:tag="..."
	 */
	public function macroTag(Tag $tag, PhpWriter $writer): void
	{
		if (!$tag->prefix || $tag->prefix !== Tag::PREFIX_NONE) {
			throw new CompileException("Unknown {$tag->getNotation()}, use n:{$tag->name} attribute.");

		} elseif (preg_match('(style$|script$)iA', $tag->htmlNode->name)) {
			throw new CompileException("Attribute {$tag->getNotation()} is not allowed in <script> or <style>");
		}

		$tag->validate(true);
	}


	/**
	 * n:tag="..."
	 */
	public function macroTagEnd(Tag $tag, PhpWriter $writer): void
	{
		for ($id = 0, $tmp = $tag->htmlNode; $tmp = $tmp->parentNode; $id++);
		$tag->htmlNode->data->id ??= $id;

		$tag->openingCode = $writer->write('<?php
			$ʟ_tag[%0_var] = (%node.args) ?? %1_var;
			Latte\Runtime\Filters::checkTagSwitch(%1_var, $ʟ_tag[%0_var]);
		?>', $tag->htmlNode->data->id, $tag->htmlNode->name);

		$tag->content = preg_replace(
			'~^(\s*<)' . Latte\Compiler\Parser::RE_TAG_NAME . '~',
			"\$1<?php echo \$ʟ_tag[{$tag->htmlNode->data->id}]; ?>\n",
			$tag->content,
		);
		$tag->content = preg_replace(
			'~</' . Latte\Compiler\Parser::RE_TAG_NAME . '(\s*>\s*)$~',
			"</<?php echo \$ʟ_tag[{$tag->htmlNode->data->id}]; ?>\n\$1",
			$tag->content,
		);
	}


	/**
	 * {dump ...}
	 */
	public function macroDump(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		$args = $writer->formatArgs();
		return $writer->write(
			'Tracy\Debugger::barDump(' . ($args ? "($args)" : 'get_defined_vars()') . ', %var) %node.line;',
			$args ?: 'variables',
		);
	}


	/**
	 * {debugbreak ...}
	 */
	public function macroDebugbreak(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		if (function_exists($func = 'debugbreak') || function_exists($func = 'xdebug_break')) {
			return $writer->write(($tag->args === '' ? '' : 'if (%node.args) ') . "$func() %node.line;");
		}

		return '';
	}


	/**
	 * {case ...}
	 */
	public function macroCase(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(true, ['switch']);
		if (isset($tag->parentNode->data->default)) {
			throw new CompileException('Tag {default} must follow after {case} clause.');
		}

		return $writer->write('} elseif (in_array($ʟ_switch, %node.array, true)) %node.line {');
	}


	/**
	 * {var ...}
	 * {default ...}
	 * {default} in {switch}
	 */
	public function macroVar(Tag $tag, PhpWriter $writer): string
	{
		if ($tag->name === 'default' && $tag->parentNode && $tag->parentNode->name === 'switch') {
			$tag->validate(false, ['switch']);
			if (isset($tag->parentNode->data->default)) {
				throw new CompileException('Tag {switch} may only contain one {default} clause.');
			}

			$tag->parentNode->data->default = true;
			return $writer->write('} else %node.line {');

		} elseif ($tag->modifiers) {
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}

		$tag->validate(true);

		$var = true;
		$hasType = false;
		$tokens = $tag->tokenizer;
		$res = new Latte\Compiler\MacroTokens;
		while ($tokens->nextToken()) {
			if ($var && !$hasType && $tokens->isCurrent($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextToken();
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
				$hasType = true;

			} elseif ($var && $tokens->isCurrent($tokens::T_VARIABLE)) {
				if ($tag->name === 'default') {
					$res->append("'" . ltrim($tokens->currentValue(), '$') . "'");
				} else {
					$res->append('$' . ltrim($tokens->currentValue(), '$'));
				}

				$var = null;

			} elseif ($var === null && $tokens->isCurrent('=')) {
				$res->append($tag->name === 'default' ? '=>' : '=');
				$var = false;

			} elseif (!$var && $tokens->isCurrent(',') && $tokens->depth === 0) {
				if ($var === null) {
					$res->append($tag->name === 'default' ? '=>null' : '=null');
				}

				$res->append($tag->name === 'default' ? ',' : ';');
				$var = true;
				$hasType = false;

			} elseif ($var === null && $tag->name === 'default' && !$tokens->isCurrent($tokens::T_WHITESPACE)) {
				throw new CompileException("Unexpected '{$tokens->currentValue()}' in {default $tag->args}");

			} else {
				$res->append($tokens->currentToken());
			}
		}

		if ($var === null) {
			$res->append($tag->name === 'default' ? '=>null' : '=null');
		} elseif ($var === true) {
			throw new CompileException("Unexpected end in {{$tag->name} {$tag->args}}");
		}

		$res = $writer->preprocess($res);
		$writer->validateKeywords($res);
		$out = $writer->quotingPass($res)->joinAll();
		return $writer->write($tag->name === 'default'
			? 'extract([%raw], EXTR_SKIP) %node.line;'
			: '%raw %node.line;', $out);
	}


	/**
	 * {= ...}
	 * {php ...}
	 * {do ...}
	 */
	public function macroExpr(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(true, [], $tag->name === '=');
		return $writer->write(
			$tag->name === '='
				? 'echo %modify(%node.args) %node.line;'
				: '%modify(%node.args) %node.line;',
		);
	}


	/**
	 * {contentType ...}
	 */
	public function macroContentType(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(true);
		if (
			!$this->getCompiler()->isInHead()
			&& !($tag->htmlNode && strtolower($tag->htmlNode->name) === 'script' && str_contains($tag->args, 'html'))
		) {
			throw new CompileException($tag->getNotation() . ' is allowed only in template header.');
		}

		$compiler = $this->getCompiler();
		if (str_contains($tag->args, 'html')) {
			$type = $compiler::CONTENT_HTML;
		} elseif (str_contains($tag->args, 'xml')) {
			$type = $compiler::CONTENT_XML;
		} elseif (str_contains($tag->args, 'javascript')) {
			$type = $compiler::CONTENT_JS;
		} elseif (str_contains($tag->args, 'css')) {
			$type = $compiler::CONTENT_CSS;
		} elseif (str_contains($tag->args, 'calendar')) {
			$type = $compiler::CONTENT_ICAL;
		} else {
			$type = $compiler::CONTENT_TEXT;
		}

		$compiler->setContentType($type);

		if (strpos($tag->args, '/') && !$tag->htmlNode) {
			return $writer->write(
				'if (empty($this->global->coreCaptured) && in_array($this->getReferenceType(), ["extends", null], true)) { header(%var) %node.line; } ',
				'Content-Type: ' . $tag->args,
			);
		}

		return '';
	}


	/**
	 * {parameters type $var, ...}
	 */
	public function macroParameters(Tag $tag, PhpWriter $writer): void
	{
		if (!$this->getCompiler()->isInHead()) {
			throw new CompileException($tag->getNotation() . ' is allowed only in template header.');
		}

		if ($tag->modifiers) {
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}

		$tag->validate(true);

		$tokens = $tag->tokenizer;
		$writer->validateKeywords($tokens);
		$params = [];
		while ($tokens->isNext(...$tokens::SIGNIFICANT)) {
			if ($tokens->nextToken($tokens::T_SYMBOL, '?', 'null', '\\')) { // type
				$tokens->nextAll($tokens::T_SYMBOL, '\\', '|', '[', ']', 'null');
			}

			$param = $tokens->consumeValue($tokens::T_VARIABLE);
			$default = $tokens->nextToken('=')
				? $tokens->joinUntilSameDepth(',')
				: 'null';
			$params[] = $writer->write(
				'%raw = $this->params[%var] ?? $this->params[%var] ?? %raw;',
				$param,
				count($params),
				substr($param, 1),
				$default,
			);
			if ($tokens->isNext(...$tokens::SIGNIFICANT)) {
				$tokens->consumeValue(',');
			}
		}

		$this->getCompiler()->paramsExtraction = implode('', $params);
	}


	/**
	 * {varType type $var}
	 */
	public function macroVarType(Tag $tag): void
	{
		if ($tag->modifiers) {
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}

		$tag->validate(true);

		$type = trim($tag->tokenizer->joinUntil($tag->tokenizer::T_VARIABLE));
		$variable = $tag->tokenizer->nextToken($tag->tokenizer::T_VARIABLE);
		if (!$type || !$variable) {
			throw new CompileException('Unexpected content, expecting {varType type $var}.');
		}
	}


	/**
	 * {varPrint [all]}
	 */
	public function macroVarPrint(Tag $tag): string
	{
		$vars = $tag->tokenizer->fetchWord() === 'all'
			? 'get_defined_vars()'
			: 'array_diff_key(get_defined_vars(), $this->getParameters())';
		return "(new Latte\\Runtime\\Blueprint)->printVars($vars); exit;";
	}


	/**
	 * {templateType ClassName}
	 */
	public function macroTemplateType(Tag $tag): void
	{
		if (!$this->getCompiler()->isInHead()) {
			throw new CompileException($tag->getNotation() . ' is allowed only in template header.');
		}

		$tag->validate('class name');
	}


	/**
	 * {templatePrint [ClassName]}
	 */
	public function macroTemplatePrint(Tag $tag): void
	{
		$this->printTemplate = PhpHelpers::dump($tag->tokenizer->fetchWord() ?: null);
	}
}
