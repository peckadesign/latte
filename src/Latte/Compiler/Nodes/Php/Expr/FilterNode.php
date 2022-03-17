<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Compiler\Nodes\Php\Expr;

use Latte\CompileException;
use Latte\Compiler\Nodes\Php;
use Latte\Compiler\Nodes\Php\IdentifierNode;
use Latte\Compiler\PrintContext;
use Latte\Context;


class FilterNode extends CallLikeNode
{
	public const Escape = 'escape';


	public function __construct(
		public ?Php\ExprNode $inner,
		public IdentifierNode $name,
		/** @var Php\ArgNode[] */
		public array $args = [],
		public ?int $line = null,
	) {
	}


	public static function escapeFilter(?self $filter): self
	{
		return new self($filter, new IdentifierNode(self::Escape));
	}


	public function print(PrintContext $context, string $expr = null): string
	{
		$name = strtolower((string) $this->name);
		$inner = $this->inner instanceof self
			? $this->inner->print($context, $expr)
			: $expr ?? $this->inner->print($context);

		if ($name === self::Escape) {
			return $this->escape($inner, ...$context->getEscapingContext());
		}
		return '($this->filters->' . $name . ')('
			. $inner
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	public function printContent(PrintContext $context, string $expr = null): string
	{
		$name = strtolower((string) $this->name);
		$inner = $this->inner instanceof self
			? $this->inner->printContent($context, $expr)
			: $expr ?? $this->inner->print($context);

		if ($name === self::Escape) {
			return 'LR\Filters::convertTo($ʟ_fi, '
				. var_export(implode('', $context->getEscapingContext()), true) . ', '
				. $inner
				. ')';
		}
		return '$this->filters->filterContent('
			. $context->encodeString($name)
			. ', $ʟ_fi, '
			. $inner
			. ($this->args ? ', ' . $context->implode($this->args) : '')
			. ')';
	}


	private function escape(string $str, string $contentType, ?string $context, ?string $flag): string
	{
		[$lq, $rq] = $flag === Context::HtmlAttributeUnquoted ? ["'\"' . ", " . '\"'"] : ['', ''];
		switch ($contentType) {
			case Context::Html:
				switch ($context) {
					case Context::HtmlText:
						return 'LR\Filters::escapeHtmlText(' . $str . ')';
					case Context::HtmlTag:
						return 'LR\Filters::escapeHtmlAttrUnquoted(' . $str . ')';
					case Context::HtmlAttribute:
					case Context::HtmlAttributeUrl:
						return 'LR\Filters::escapeHtmlAttr(' . $str . ')';
					case Context::HtmlAttributeJavaScript:
						return $lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeJs(' . $str . '))' . $rq;
					case Context::HtmlAttributeCss:
						return $lq . 'LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss(' . $str . '))' . $rq;
					case Context::HtmlComment:
						return 'LR\Filters::escapeHtmlComment(' . $str . ')';
					case Context::HtmlBogusTag:
						return 'LR\Filters::escapeHtml(' . $str . ')';
					case Context::HtmlJavaScript:
					case Context::HtmlCss:
						return 'LR\Filters::escape' . ucfirst($context) . '(' . $str . ')';
					default:
						throw new CompileException("Unknown context $contentType, $context.");
				}
			// break omitted
			case Context::Xml:
				switch ($context) {
					case Context::XmlText:
					case Context::XmlAttribute:
					case Context::XmlBogusTag:
						return 'LR\Filters::escapeXml(' . $str . ')';
					case Context::XmlComment:
						return 'LR\Filters::escapeHtmlComment(' . $str . ')';
					case Context::XmlTag:
						return 'LR\Filters::escapeXmlAttrUnquoted(' . $str . ')';
					default:
						throw new CompileException("Unknown context $contentType, $context.");
				}
			// break omitted
			case Context::JavaScript:
			case Context::Css:
			case Context::ICal:
				return 'LR\Filters::escape' . ucfirst($contentType) . '(' . $str . ')';
			case Context::Text:
				return $str;
			case null:
				return '($this->filters->escape)(' . $str . ')';
			default:
				throw new CompileException("Unknown context $contentType.");
		}
	}


	public function &getIterator(): \Generator
	{
		if ($this->inner) {
			yield $this->inner;
		}
		yield $this->name;
		foreach ($this->args as &$item) {
			yield $item;
		}
	}
}
