<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Html;

use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\AuxiliaryNode;
use Latte\Compiler\Nodes\FragmentNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Context;
use Latte\Helpers;


/**
 * HTML element node.
 */
class ElementNode extends AreaNode
{
	public ?AreaNode $content = null;
	public ?Node $variableName = null;
	public ?string $endIndentation = '';
	public bool $endNewline = false;

	/** @var Tag[] */
	public array $nAttrs = [];

	/** n:tag & n:tag- support */
	public AreaNode $tagNode;
	public bool $specialTag = false;
	private ?string $endTagVar;


	public function __construct(
		public string $name,
		public ?FragmentNode $attrs = null,
		public bool $selfClosing = false,
		public ?string $indentation = '',
		public bool $newline = false,
		public ?int $line = null,
		public ?self $parent = null,
	) {
		$this->tagNode = new AuxiliaryNode(\Closure::fromCallable([$this, 'compileStartTag']));
	}


	public function getAttribute(string $name): string|Node|bool|null
	{
		foreach ($this->attrs?->children as $child) {
			if ($child instanceof AttributeNode
				&& strcasecmp($name, $child->name) === 0
			) {
				return Helpers::nodeToString($child->value) ?? $child->value ?? true;
			}
		}

		return null;
	}


	public function print(PrintContext $context): string
	{
		$res = $this->endTagVar = null;
		if ($this->specialTag || $this->variableName) {
			$endTag = $this->endTagVar = '$ʟ_tag[' . $context->generateId() . ']';
			$res = "$this->endTagVar = '';";
		} else {
			$endTag = var_export($this->endIndentation . '</' . $this->name . '>' . ($this->endNewline ? "\n" : ''), true);
		}

		$res .= $this->tagNode->print($context); // calls $this->compileStartTag()

		$this->setInnerContext($context);
		if ($this->content) {
			$res .= $this->content->print($context);
			$context->setEscapingContext(Context::HtmlText);
			$res .= 'echo ' . $endTag . ';';
		}

		return $res;
	}


	private function compileStartTag(PrintContext $context): string
	{
		$context->setEscapingContext(Context::HtmlTag);
		$res = 'echo ' . var_export($this->indentation . '<', true) . ';';

		$namePhp = var_export($this->name, true);
		if ($this->endTagVar) {
			$res .= 'echo $ʟ_tmp = (' . ($this->variableName ? $this->variableName->print($context) : $namePhp) . ');';
			$res .= $this->endTagVar . ' = '
				. var_export($this->endIndentation . '</', true) . ' . $ʟ_tmp . ' . var_export('>' . ($this->endNewline ? "\n" : ''), true)
				. ' . ' . $this->endTagVar . ';';
		} else {
			$res .= 'echo ' . $namePhp . ';';
		}

		foreach ($this->attrs?->children ?? [] as $attr) {
			if ($attr instanceof AttributeNode) {
				$this->setAttributeContext($context, $attr);
			}
			$res .= $attr->print($context);
		}

		$res .= 'echo ' . var_export(($this->selfClosing ? '/>' : '>') . ($this->newline ? "\n" : ''), true) . ';';
		return $res;
	}


	private function setInnerContext(PrintContext $context): void
	{
		$name = strtolower($this->name);
		if (
			$context->getContentType() === Context::Html
			&& !$this->selfClosing
			&& ($name === 'script' || $name === 'style')
			&& is_string($attr = $this->getAttribute('type') ?? 'css')
			&& preg_match('#(java|j|ecma|live)script|module|json|css|plain#i', $attr)
		) {
			$context->setEscapingContext($name === 'script'
				? Context::HtmlJavaScript
				: Context::HtmlCss);
		} else {
			$context->setEscapingContext(Context::HtmlText);
		}
	}


	private function setAttributeContext(PrintContext $context, AttributeNode $attr): void
	{
		if ($context->getContentType() !== Context::Html) {
			$context->setEscapingContext($attr->quote ? Context::XmlAttribute : Context::XmlTag);
			return;
		}

		$attrName = strtolower($attr->name);

		if ($attr->quote) {
			$escapingContext = Context::HtmlAttribute;
			if (str_starts_with($attrName, 'on')) {
				$escapingContext = Context::HtmlAttributeJavaScript;
			} elseif ($attrName === 'style') {
				$escapingContext = Context::HtmlAttributeCss;
			}
		} else {
			$escapingContext = Context::HtmlTag;
		}

		if ((in_array($attrName, ['href', 'src', 'action', 'formaction'], true)
			|| ($attrName === 'data' && strtolower($this->name) === 'object'))
		) {
			$escapingContext = $escapingContext === Context::HtmlTag
				? Context::HtmlAttributeUnquotedUrl
				: Context::HtmlAttributeUrl;
		}

		$context->setEscapingContext($escapingContext);
	}
}
