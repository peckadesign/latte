<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Macros;

use Latte;
use Latte\CompileException;
use Latte\Compiler\Block;
use Latte\Compiler\PhpHelpers;
use Latte\Compiler\PhpWriter;
use Latte\Compiler\Tag;
use Latte\Helpers;
use Latte\Runtime\SnippetDriver;
use Latte\Runtime\Template;


/**
 * Block macros.
 */
class BlockMacros extends MacroSet
{
	public string $snippetAttribute = 'id';

	/** @var Block[][] */
	private array $blocks;

	/** current layer */
	private int $index;

	private string|bool|null $extends = null;

	/** @var string[] */
	private array $imports;

	/** @var array[] */
	private array $placeholders;


	public static function install(Latte\Compiler\TemplateGenerator $compiler): void
	{
		$me = new static($compiler);
		$me->addMacro('include', [$me, 'macroInclude']);
		$me->addMacro('import', [$me, 'macroImport'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('extends', [$me, 'macroExtends'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('layout', [$me, 'macroExtends'], null, null, self::ALLOWED_IN_HEAD);
		$me->addMacro('snippet', [$me, 'macroSnippet'], [$me, 'macroBlockEnd']); // must be before block
		$me->addMacro('block', [$me, 'macroBlock'], [$me, 'macroBlockEnd'], null, self::AUTO_CLOSE);
		$me->addMacro('define', [$me, 'macroDefine'], [$me, 'macroBlockEnd']);
		$me->addMacro('embed', [$me, 'macroEmbed'], [$me, 'macroEmbedEnd']);
		$me->addMacro('snippetArea', [$me, 'macroSnippetArea'], [$me, 'macroBlockEnd']);
		$me->addMacro('ifset', [$me, 'macroIfset'], '}');
		$me->addMacro('elseifset', [$me, 'macroIfset']);
	}


	public function beforeCompile(): void
	{
		$this->blocks = [[]];
		$this->index = Template::LayerTop;
		$this->extends = null;
		$this->imports = [];
		$this->placeholders = [];
	}


	public function finalize()
	{
		$compiler = $this->getCompiler();
		foreach ($this->placeholders as $key => [$index, $blockName]) {
			$block = $this->blocks[$index][$blockName] ?? $this->blocks[Template::LayerLocal][$blockName] ?? null;
			$compiler->placeholders[$key] = $block && !$block->hasParameters
				? 'get_defined_vars()'
				: '[]';
		}

		$meta = [];
		foreach ($this->blocks as $layer => $blocks) {
			foreach ($blocks as $name => $block) {
				$compiler->addMethod(
					$method = $this->generateMethodName($name),
					'?>' . $compiler->expandTokens($block->code) . '<?php',
					'array $ʟ_args',
					'void',
					$block->comment,
				);
				$meta[$layer][$name] = $block->contentType === $compiler->getContentType()
					? $method
					: [$method, $block->contentType];
			}
		}

		if ($meta) {
			$compiler->addConstant('Blocks', $meta);
		}

		return [
			($this->extends === null ? '' : '$this->parentName = ' . $this->extends . ';') . implode('', $this->imports),
		];
	}


	/********************* macros ****************d*g**/


	/**
	 * {include [block] name [,] [params]}
	 */
	public function macroInclude(Tag $tag, PhpWriter $writer): string|false
	{
		$tag->validate(true, [], true);
		$tag->replaced = false;

		$tmp = $tag->tokenizer->joinUntil('=');
		if ($tag->tokenizer->isNext('=') && !$tag->tokenizer->depth) {
			trigger_error('The assignment in the {' . $tag->name . ' ' . $tmp . '= ...} looks like an error.', E_USER_NOTICE);
		}

		$tag->tokenizer->reset();

		[$name, $mod] = $tag->tokenizer->fetchWordWithModifier(['block', 'file', '#']);
		if (!$mod && preg_match('~([\'"])[\w-]+\\1$~DA', $name)) {
			trigger_error("Change {include $name} to {include file $name} for clarity on line $tag->startLine", E_USER_NOTICE);
		}
		if ($mod !== 'block' && $mod !== '#'
			&& ($mod === 'file' || !$name || !preg_match('~[\w-]+$~DA', $name))
		) {
			return false; // {include file}
		}

		if ($name === 'parent' && $tag->modifiers !== '') {
			throw new CompileException('Filters are not allowed in {include parent}');
		}

		$noEscape = Helpers::removeFilter($tag->modifiers, 'noescape');
		if ($tag->modifiers && !$noEscape) {
			$tag->modifiers .= '|escape';
		}

		if ($tag->tokenizer->nextToken('from')) {
			$tag->tokenizer->nextToken($tag->tokenizer::T_WHITESPACE);
			return $writer->write(
				'$this->createTemplate(%node.word, %node.array? + $this->params, "include")->renderToContentType(%raw, %word) %node.line;',
				$tag->modifiers
					? $writer->write('function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }')
					: PhpHelpers::dump($noEscape ? null : implode('', $tag->context)),
				$name,
			);
		}

		$parent = $name === 'parent';
		if ($name === 'parent' || $name === 'this') {
			$item = $tag->closest(['block', 'define'], fn($tag) => $tag->data->name !== '');
			if (!$item) {
				throw new CompileException("Cannot include $name block outside of any block.");
			}

			$name = $item->data->name;
		}

		$key = uniqid() . '$iterator'; // to fool CoreMacros::macroEndForeach
		$this->placeholders[$key] = [$this->index, $name];
		$phpName = $this->isDynamic($name)
			? $writer->formatWord($name)
			: PhpHelpers::dump($name);

		return $writer->write(
			'$this->renderBlock' . ($parent ? 'Parent' : '')
			. '(' . $phpName . ', '
			. '%node.array? + ' . $key
			. ($tag->modifiers
				? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				: ($noEscape || $parent ? '' : ', ' . PhpHelpers::dump(implode('', $tag->context))))
			. ') %node.line;',
		);
	}


	/**
	 * {import "file"}
	 */
	public function macroImport(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(true);
		$file = $tag->tokenizer->fetchWord();
		$this->checkExtraArgs($tag);
		$code = $writer->write('$this->createTemplate(%word, $this->params, "import")->render() %node.line;', $file);
		if ($this->getCompiler()->isInHead()) {
			$this->imports[] = $code;
			return '';
		} elseif ($tag->parentNode && $tag->parentNode->name === 'embed') {
			return "} $code if (false) {";
		} else {
			return $code;
		}
	}


	/**
	 * {extends none | $var | "file"}
	 */
	public function macroExtends(Tag $tag, PhpWriter $writer): void
	{
		$tag->validate(true);
		if ($tag->parentNode) {
			throw new CompileException($tag->getNotation() . ' must not be inside other tags.');
		} elseif ($this->extends !== null) {
			throw new CompileException('Multiple ' . $tag->getNotation() . ' declarations are not allowed.');
		} elseif ($tag->args === 'none') {
			$this->extends = 'false';
		} else {
			$this->extends = $writer->write('%node.word%node.args');
		}

		if (!$this->getCompiler()->isInHead()) {
			throw new CompileException($tag->getNotation() . ' must be placed in template head.');
		}
	}


	/**
	 * {block [local] [name]}
	 */
	public function macroBlock(Tag $tag, PhpWriter $writer): string
	{
		[$name, $local] = $tag->tokenizer->fetchWordWithModifier('local');
		$layer = $local ? Template::LayerLocal : null;
		$data = $tag->data;
		$data->name = ltrim((string) $name, '#');
		$this->checkExtraArgs($tag);

		if ($data->name === '') {
			if ($tag->modifiers === '') {
				return '';
			}

			$tag->modifiers .= '|escape';
			$tag->closingCode = $writer->write(
				'<?php } finally { $ʟ_fi = new LR\FilterInfo(%var); echo %modifyContent(ob_get_clean()); } ?>',
				implode('', $tag->context),
			);
			return $writer->write("ob_start(fn() => '') %node.line; try {");
		}

		if (str_starts_with((string) $tag->context[1], Latte\Context::HtmlAttribute)) {
			$tag->context[1] = '';
			$tag->modifiers .= '|escape';
		} elseif ($tag->modifiers) {
			$tag->modifiers .= '|escape';
		}

		$renderArgs = $writer->write(
			'get_defined_vars()'
			. ($tag->modifiers ? ', function ($s, $type) { $ʟ_fi = new LR\FilterInfo($type); return %modifyContent($s); }' : ''),
		);

		if ($this->isDynamic($data->name)) {
			$tag->closingCode = $writer->write('<?php $this->renderBlock($ʟ_nm, %raw); ?>', $renderArgs);
			return $this->beginDynamicBlockOrDefine($tag, $writer, $layer);
		}

		if (!preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Block name must start with letter a-z, '$data->name' given.");
		}

		$extendsCheck = $this->blocks[Template::LayerTop] || count($this->blocks) > 1 || $tag->parentNode;
		$block = $this->addBlock($tag, $layer);

		$data->after = function () use ($tag, $block) {
			$this->extractMethod($tag, $block);
		};

		return $writer->write(
			($extendsCheck ? '' : 'if ($this->getParentName()) { return get_defined_vars(); } ')
			. '$this->renderBlock(%var, %raw) %node.line;',
			$data->name,
			$renderArgs,
		);
	}


	/**
	 * {define [local] name}
	 */
	public function macroDefine(Tag $tag, PhpWriter $writer): string
	{
		if ($tag->modifiers) { // modifier may be union|type
			$tag->setArgs($tag->args . $tag->modifiers);
			$tag->modifiers = '';
		}

		$tag->validate(true);

		[$name, $local] = $tag->tokenizer->fetchWordWithModifier('local');
		$layer = $local ? Template::LayerLocal : null;
		$data = $tag->data;
		$data->name = ltrim((string) $name, '#');

		if ($this->isDynamic($data->name)) {
			$tag->closingCode = '<?php ?>';
			return $this->beginDynamicBlockOrDefine($tag, $writer, $layer);
		}

		if (!preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Block name must start with letter a-z, '$data->name' given.");
		}

		$tokens = $tag->tokenizer;
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
				'%raw = $ʟ_args[%var] ?? $ʟ_args[%var] ?? %raw;',
				$param,
				count($params),
				substr($param, 1),
				$default,
			);
			if ($tokens->isNext(...$tokens::SIGNIFICANT)) {
				$tokens->consumeValue(',');
			}
		}

		$extendsCheck = $this->blocks[Template::LayerTop] || count($this->blocks) > 1 || $tag->parentNode;
		$block = $this->addBlock($tag, $layer);
		$block->hasParameters = (bool) $params;

		$data->after = function () use ($tag, $block, $params) {
			$params = $params ? implode('', $params) : null;
			$this->extractMethod($tag, $block, $params);
		};

		return $extendsCheck
			? ''
			: 'if ($this->getParentName()) { return get_defined_vars();} ';
	}


	private function beginDynamicBlockOrDefine(Tag $tag, PhpWriter $writer, ?string $layer): string
	{
		$this->checkExtraArgs($tag);
		$data = $tag->data;
		$func = $this->generateMethodName($data->name);

		$data->after = function () use ($tag, $func) {
			$tag->content = rtrim($tag->content, " \t");
			$this->getCompiler()->addMethod(
				$func,
				$this->getCompiler()->expandTokens("extract(\$ʟ_args); unset(\$ʟ_args);\n?>{$tag->content}<?php"),
				'array $ʟ_args',
				'void',
				"{{$tag->name} {$tag->args}} on line {$tag->line}",
			);
			$tag->content = '';
		};

		return $writer->write(
			'$this->addBlock($ʟ_nm = %word, %var, [[$this, %var]], %var);',
			$data->name,
			implode('', $tag->context),
			$func,
			$layer,
		);
	}


	/**
	 * {snippet [name]}
	 */
	public function macroSnippet(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		$data = $tag->data;
		$data->name = (string) $tag->tokenizer->fetchWord();
		$this->checkExtraArgs($tag);

		if ($tag->prefix && isset($tag->htmlNode->attrs[$this->snippetAttribute])) {
			throw new CompileException("Cannot combine HTML attribute {$this->snippetAttribute} with n:snippet.");

		} elseif ($tag->prefix && isset($tag->htmlNode->macroAttrs['ifcontent'])) {
			throw new CompileException('Cannot combine n:ifcontent with n:snippet.');

		} elseif ($this->isDynamic($data->name)) {
			return $this->beginDynamicSnippet($tag, $writer);

		} elseif ($data->name !== '' && !preg_match('#^[a-z]#iD', $data->name)) {
			throw new CompileException("Snippet name must start with letter a-z, '$data->name' given.");
		}

		if ($tag->prefix && $tag->prefix !== $tag::PREFIX_NONE) {
			trigger_error("Use n:snippet instead of {$tag->getNotation()}", E_USER_DEPRECATED);
		}

		$block = $this->addBlock($tag, Template::LayerSnippet);

		$data->after = function () use ($tag, $writer, $data, $block) {
			if ($tag->prefix === Tag::PREFIX_NONE) { // n:snippet -> n:inner-snippet
				$tag->content = $tag->innerContent;
			}

			$tag->content = $writer->write(
				'<?php $this->global->snippetDriver->enter(%word, %var);
				try { ?>%raw<?php } finally { $this->global->snippetDriver->leave(); } ?>',
				$data->name,
				SnippetDriver::TYPE_STATIC,
				preg_replace('#(?<=\n)[ \t]+$#D', '', $tag->content),
			);

			$this->extractMethod($tag, $block);

			if ($tag->prefix === Tag::PREFIX_NONE) {
				$tag->innerContent = $tag->openingCode . $tag->content . $tag->closingCode;
				$tag->closingCode = $tag->openingCode = '<?php ?>';
			}
		};

		if ($tag->prefix) {
			if (isset($tag->htmlNode->macroAttrs['foreach'])) {
				throw new CompileException('Combination of n:snippet with n:foreach is invalid, use n:inner-foreach.');
			}

			$tag->attrCode = $writer->write(
				"<?php echo ' {$this->snippetAttribute}=\"' . htmlspecialchars(\$this->global->snippetDriver->getHtmlId(%var)) . '\"' ?>",
				$data->name,
			);
			return $writer->write('$this->renderBlock(%var, [], null, %var)', $data->name, Template::LayerSnippet);
		}

		return $writer->write(
			"?>\n<div {$this->snippetAttribute}=\"<?php echo htmlspecialchars(\$this->global->snippetDriver->getHtmlId(%0_var)) ?>\">"
			. '<?php $this->renderBlock(%0_var, [], null, %1_var) %node.line; ?>'
			. "\n</div><?php ",
			$data->name,
			Template::LayerSnippet,
		);
	}


	private function beginDynamicSnippet(Tag $tag, PhpWriter $writer): string
	{
		$data = $tag->data;
		$tag->closingCode = '<?php } finally { $this->global->snippetDriver->leave(); } ?>';

		if ($tag->prefix) {
			if ($tag->prefix === Tag::PREFIX_NONE) { // n:snippet -> n:inner-snippet
				$data->after = function () use ($tag) {
					$tag->innerContent = $tag->openingCode . $tag->innerContent . $tag->closingCode;
					$tag->closingCode = $tag->openingCode = '<?php ?>';
				};
			}

			$tag->attrCode = $writer->write(
				"<?php echo ' {$this->snippetAttribute}=\"' . htmlspecialchars(\$this->global->snippetDriver->getHtmlId(\$ʟ_nm = %word)) . '\"' ?>",
				$data->name,
			);
			return $writer->write('$this->global->snippetDriver->enter($ʟ_nm, %var) %node.line; try {', SnippetDriver::TYPE_DYNAMIC);
		}

		$tag->closingCode .= "\n</div>";
		return $writer->write(
			"?>\n<div {$this->snippetAttribute}=\""
			. '<?php echo htmlspecialchars($this->global->snippetDriver->getHtmlId($ʟ_nm = %word)) ?>"'
			. '><?php $this->global->snippetDriver->enter($ʟ_nm, %var) %node.line; try {',
			$data->name,
			SnippetDriver::TYPE_DYNAMIC,
		);
	}


	/**
	 * {snippetArea [name]}
	 */
	public function macroSnippetArea(Tag $tag, PhpWriter $writer): string
	{
		$tag->validate(null);
		$data = $tag->data;
		$data->name = (string) $tag->tokenizer->fetchWord();
		$this->checkExtraArgs($tag);

		$block = $this->addBlock($tag, Template::LayerSnippet);

		$data->after = function () use ($tag, $writer, $data, $block) {
			$tag->content = $writer->write(
				'<?php $this->global->snippetDriver->enter(%var, %var);
				try { ?>%raw<?php } finally { $this->global->snippetDriver->leave(); } ?>',
				$data->name,
				SnippetDriver::TYPE_AREA,
				preg_replace('#(?<=\n)[ \t]+$#D', '', $tag->content),
			);
			$this->extractMethod($tag, $block);
		};
		return $writer->write('$this->renderBlock(%var, [], null, %var) %node.line;', $data->name, Template::LayerSnippet);
	}


	/**
	 * {/block}
	 * {/define}
	 * {/snippet}
	 * {/snippetArea}
	 */
	public function macroBlockEnd(Tag $tag, PhpWriter $writer): string
	{
		if (isset($tag->data->after)) {
			($tag->data->after)();
		}

		return $tag->name === 'define'
			? ' ' // consume next new line
			: '';
	}


	private function addBlock(Tag $tag, ?string $layer = null): Block
	{
		$data = $tag->data;
		if ($layer === Template::LayerSnippet
			? isset($this->blocks[$layer][$data->name])
			: (isset($this->blocks[Template::LayerLocal][$data->name]) || isset($this->blocks[$this->index][$data->name]))
		) {
			throw new CompileException("Cannot redeclare {$tag->name} '{$data->name}'");
		}

		$block = $this->blocks[$layer ?? $this->index][$data->name] = new Block;
		$block->contentType = implode('', $tag->context);
		$block->comment = "{{$tag->name} {$tag->args}} on line {$tag->line}";
		return $block;
	}


	private function extractMethod(Tag $tag, Block $block, ?string $params = null): void
	{
		if (preg_match('#\$|n:#', $tag->content)) {
			$tag->content = '<?php extract(' . ($tag->name === 'block' && $tag->closest(['embed']) ? 'end($this->varStack)' : '$this->params') . ');'
				. ($params ?? 'extract($ʟ_args);')
				. 'unset($ʟ_args);?>'
				. $tag->content;
		}

		$block->code = preg_replace('#^\n+|(?<=\n)[ \t]+$#D', '', $tag->content);
		$tag->content = substr_replace($tag->content, $tag->openingCode . "\n", strspn($tag->content, "\n"), strlen($block->code));
		$tag->openingCode = '<?php ?>';
	}


	/**
	 * {embed [block|file] name [,] [params]}
	 */
	public function macroEmbed(Tag $tag, PhpWriter $writer): void
	{
		$tag->validate(true);
		$tag->replaced = false;
		$tag->data->prevIndex = $this->index;
		$this->index = count($this->blocks);
		$this->blocks[$this->index] = [];

		[$name, $mod] = $tag->tokenizer->fetchWordWithModifier(['block', 'file']);
		if (!$mod && preg_match('~([\'"])[\w-]+\\1$~DA', $name)) {
			trigger_error("Change {embed $name} to {embed file $name} for clarity on line $tag->startLine", E_USER_NOTICE);
		}
		$mod ??= (preg_match('~^[\w-]+$~DA', $name) ? 'block' : 'file');

		$tag->openingCode = $writer->write(
			'<?php
			$this->enterBlockLayer(%0_var, get_defined_vars()) %node.line;
			if (false) { ?>',
			$this->index,
		);

		if ($mod === 'file') {
			$tag->closingCode = $writer->write(
				'<?php }
				try { $this->createTemplate(%word, %node.array, "embed")->renderToContentType(%var) %node.line; }
				finally { $this->leaveBlockLayer(); } ?>' . "\n",
				$name,
				implode('', $tag->context),
			);

		} else {
			$tag->closingCode = $writer->write(
				'<?php }
				$this->copyBlockLayer();
				try { $this->renderBlock(%raw, %node.array, %var) %node.line; }
				finally { $this->leaveBlockLayer(); } ?>' . "\n",
				$this->isDynamic($name) ? $writer->formatWord($name) : PhpHelpers::dump($name),
				implode('', $tag->context),
			);
		}
	}


	/**
	 * {/embed}
	 */
	public function macroEmbedEnd(Tag $tag, PhpWriter $writer): void
	{
		$this->index = $tag->data->prevIndex;
	}


	/**
	 * {ifset block}
	 * {elseifset block}
	 */
	public function macroIfset(Tag $tag, PhpWriter $writer): string|false
	{
		$tag->validate(true);
		if (!preg_match('~#|\w~A', $tag->args)) {
			return false;
		}

		$list = [];
		while ([$name, $block] = $tag->tokenizer->fetchWordWithModifier(['block', '#'])) {
			$list[] = $block || preg_match('~\w[\w-]*$~DA', $name)
				? '$this->hasBlock(' . $writer->formatWord($name) . ')'
				: 'isset(' . $writer->formatArgs(new Latte\Compiler\MacroTokens($name)) . ')';
		}

		return $writer->write(($tag->name === 'elseifset' ? '} else' : '') . 'if (%raw) %node.line {', implode(' && ', $list));
	}


	private function generateMethodName(string $blockName): string
	{
		$name = 'block' . ucfirst(trim(preg_replace('#\W+#', '_', $blockName), '_'));
		$lower = strtolower($name);
		$methods = array_change_key_case($this->getCompiler()->getMethods()) + ['block' => 1];
		$counter = null;
		while (isset($methods[$lower . $counter])) {
			$counter++;
		}

		return $name . $counter;
	}


	private function isDynamic(string $name): bool
	{
		return str_contains($name, '$') || str_contains($name, ' ');
	}
}
