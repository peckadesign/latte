<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * The exception occurred during Latte compilation.
 */
class CompileException extends \Exception
{
	public ?int $sourceLine;
	public ?int $sourceColumn;
	public ?string $sourceCode = null;
	public ?string $sourceName = null;
	private string $origMessage;


	public function __construct(string $message, ?int $line = null, ?int $column = null, ?\Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->origMessage = $message;
		$this->sourceLine = $line;
		$this->sourceColumn = $column;
		$this->generateMessage();
	}


	public function setSource(string $code, ?int $line = null, ?string $name = null): self
	{
		$this->sourceCode = $code;
		$this->sourceLine ??= $line;
		$this->sourceName = $name;
		$this->generateMessage();
		return $this;
	}


	private function generateMessage()
	{
		$info = [];
		if ($this->sourceName && @is_file($this->sourceName)) { // @ - may trigger error
			$info[] = "in '" . str_replace(dirname($this->sourceName, 2), '...', $this->sourceName) . "'";
		}
		if ($this->sourceLine > 1) {
			$info[] = 'on line ' . $this->sourceLine;
		}
		if ($this->sourceColumn) {
			$info[] = 'at column ' . $this->sourceColumn;
		}
		$this->message = $info
			? rtrim($this->origMessage, '.') . ' (' . implode(' ', $info) . ')'
			: $this->origMessage;
	}
}
