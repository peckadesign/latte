<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Templating engine Latte.
 */
class Engine
{
	use Strict;

	public const VERSION = '3.0.0-dev';
	public const VERSION_ID = 30000;

	/** @deprecated use Context::* */
	public const
		CONTENT_HTML = Context::Html,
		CONTENT_XML = Context::Xml,
		CONTENT_JS = Context::JavaScript,
		CONTENT_CSS = Context::Css,
		CONTENT_ICAL = Context::ICal,
		CONTENT_TEXT = Context::Text;

	/** @deprecated and unused */
	public $onCompile = [];

	/** @internal */
	public $probe;

	private ?Loader $loader = null;
	private Runtime\FilterExecutor $filters;
	private \stdClass $functions;

	/** @var mixed[] */
	private array $providers = [];

	/** @var Extension[] */
	private array $extensions = [];
	private string $contentType = Context::Html;
	private ?string $tempDirectory = null;
	private bool $autoRefresh = true;
	private bool $strictTypes = false;
	private ?Policy $policy = null;
	private bool $sandboxed = false;


	public function __construct()
	{
		$this->filters = new Runtime\FilterExecutor;
		$this->functions = new \stdClass;
		$this->probe = function () {};
		$this->addExtension(new Essential\Extension);
		$this->addExtension(new Sandbox\Extension);
	}


	/**
	 * Renders template to output.
	 * @param  object|mixed[]  $params
	 */
	public function render(string $name, object|array $params = [], ?string $block = null): void
	{
		$template = $this->createTemplate($name, $this->processParams($params));
		$template->global->coreCaptured = false;
		($this->probe)($template);
		$template->render($block);
	}


	/**
	 * Renders template to string.
	 * @param  object|mixed[]  $params
	 */
	public function renderToString(string $name, object|array $params = [], ?string $block = null): string
	{
		$template = $this->createTemplate($name, $this->processParams($params));
		$template->global->coreCaptured = true;
		($this->probe)($template);
		return $template->capture(function () use ($template, $block) { $template->render($block); });
	}


	/**
	 * Creates template object.
	 * @param  mixed[]  $params
	 */
	public function createTemplate(string $name, array $params = []): Runtime\Template
	{
		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}

		foreach ($this->extensions as $extension) {
			$extension->beforeRender($this);
		}

		$this->providers['fn'] = $this->functions;
		return new $class(
			$this,
			$params,
			$this->filters,
			$this->providers,
			$name,
		);
	}


	/**
	 * Compiles template to PHP code.
	 */
	public function compile(string $name): string
	{
		if ($this->sandboxed && !$this->policy) {
			throw new \LogicException('In sandboxed mode you need to set a security policy.');
		}

		$lexer = $this->createLexer();
		$parser = $this->createParser();
		$context = new Compiler\PrintContext;
		$generator = $this->createGenerator();
		$passes = [];

		foreach ($this->extensions as $extension) {
			$extension->beforeCompile($this);
			$parser->addTags($extension->getTags());
			foreach ($extension->getPasses() as $priority => $pass) {
				$passes[] = [$pass, $priority];
			}
		}

		$source = $this->getLoader()->getContent($name);
		$comment = preg_match('#\n|\?#', $name) ? null : "source: $name";

		try {
			$node = $parser
				->setContentType($this->contentType)
				->setPolicy($this->getPolicy(effective: true))
				->parse($source, $lexer);

			$context->setContentType($parser->getContentType());

			usort($passes, fn($a, $b) => $a[1] <=> $b[1]);
			foreach ($passes as [$pass]) {
				$node = $pass($node, $context) ?? $node;
			}

			$code = $generator->generate(
				$context,
				$node,
				$this->getTemplateClass($name),
				$comment,
				$this->strictTypes,
			);

		} catch (\Throwable $e) {
			if (!$e instanceof CompileException) {
				$e = new CompileException($e instanceof SecurityViolationException
					? $e->getMessage()
					: "Thrown exception '{$e->getMessage()}'", 0, 0, $e);
			}

			throw $e->setSource($source, $name);
		}

		return $code;
	}


	/**
	 * Compiles template to cache.
	 * @throws \LogicException
	 */
	public function warmupCache(string $name): void
	{
		if (!$this->tempDirectory) {
			throw new \LogicException('Path to temporary directory is not set.');
		}

		$class = $this->getTemplateClass($name);
		if (!class_exists($class, false)) {
			$this->loadTemplate($name);
		}
	}


	private function loadTemplate(string $name): void
	{
		if (!$this->tempDirectory) {
			$code = $this->compile($name);
			if (@eval(substr($code, 5)) === false) { // @ is escalated to exception, substr removes <?php
				throw (new CompileException('Error in template: ' . error_get_last()['message']))
					->setSource($code, "$name (compiled)");
			}

			return;
		}

		// Solving atomicity to work everywhere is really pain in the ass.
		// 1) We want to do as little as possible IO calls on production and also directory and file can be not writable
		// so on Linux we include the file directly without shared lock, therefore, the file must be created atomically by renaming.
		// 2) On Windows file cannot be renamed-to while is open (ie by include), so we have to acquire a lock.
		$file = $this->getCacheFile($name);
		$lock = defined('PHP_WINDOWS_VERSION_BUILD')
			? $this->acquireLock("$file.lock", LOCK_SH)
			: null;

		if (!$this->isExpired($file, $name) && (@include $file) !== false) { // @ - file may not exist
			return;
		}

		if ($lock) {
			flock($lock, LOCK_UN); // release shared lock so we can get exclusive
		}

		$lock = $this->acquireLock("$file.lock", LOCK_EX);

		// while waiting for exclusive lock, someone might have already created the cache
		if (!is_file($file) || $this->isExpired($file, $name)) {
			$code = $this->compile($name);
			if (file_put_contents("$file.tmp", $code) !== strlen($code) || !rename("$file.tmp", $file)) {
				@unlink("$file.tmp"); // @ - file may not exist
				throw new RuntimeException("Unable to create '$file'.");
			}

			if (function_exists('opcache_invalidate')) {
				@opcache_invalidate($file, true); // @ can be restricted
			}
		}

		if ((include $file) === false) {
			throw new RuntimeException("Unable to load '$file'.");
		}
	}


	/**
	 * @return resource
	 */
	private function acquireLock(string $file, int $mode)
	{
		$dir = dirname($file);
		if (!is_dir($dir) && !@mkdir($dir) && !is_dir($dir)) { // @ - dir may already exist
			throw new RuntimeException("Unable to create directory '$dir'. " . error_get_last()['message']);
		}

		$handle = @fopen($file, 'w'); // @ is escalated to exception
		if (!$handle) {
			throw new RuntimeException("Unable to create file '$file'. " . error_get_last()['message']);
		} elseif (!@flock($handle, $mode)) { // @ is escalated to exception
			throw new RuntimeException('Unable to acquire ' . ($mode & LOCK_EX ? 'exclusive' : 'shared') . " lock on file '$file'. " . error_get_last()['message']);
		}

		return $handle;
	}


	private function isExpired(string $file, string $name): bool
	{
		return $this->autoRefresh && $this->getLoader()->isExpired($name, (int) @filemtime($file)); // @ - file may not exist
	}


	public function getCacheFile(string $name): string
	{
		$hash = substr($this->getTemplateClass($name), 8);
		$base = preg_match('#([/\\\\][\w@.-]{3,35}){1,3}$#D', $name, $m)
			? preg_replace('#[^\w@.-]+#', '-', substr($m[0], 1)) . '--'
			: '';
		return "$this->tempDirectory/$base$hash.php";
	}


	public function getTemplateClass(string $name): string
	{
		$key = serialize([
			$this->getLoader()->getUniqueId($name),
			self::VERSION,
			array_keys((array) $this->functions),
			$this->sandboxed,
		]);
		return 'Template' . substr(md5($key), 0, 10);
	}


	/**
	 * Registers run-time filter.
	 */
	public function addFilter(?string $name, callable $callback): static
	{
		if ($name !== null && !preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid filter name '$name'.");
		}

		$this->filters->add($name, $callback);
		return $this;
	}


	/**
	 * Registers run-time filter loader.
	 */
	public function addFilterLoader(callable $callback): static
	{
		$this->filters->add(null, function ($name) use ($callback) {
			if ($filter = $callback($name)) {
				$this->filters->add($name, $filter);
			}
		});
		return $this;
	}


	/**
	 * Returns all run-time filters.
	 * @return string[]
	 */
	public function getFilters(): array
	{
		return $this->filters->getAll();
	}


	/**
	 * Call a run-time filter.
	 * @param  mixed[]  $args
	 */
	public function invokeFilter(string $name, array $args): mixed
	{
		return ($this->filters->$name)(...$args);
	}


	/**
	 * Adds new extension.
	 */
	public function addExtension(Extension $extension): static
	{
		$this->extensions[] = $extension;
		foreach ($extension->getFilters() as $name => $callback) {
			$this->filters->add($name, $callback);
		}

		foreach ($extension->getFunctions() as $name => $callback) {
			$this->functions->$name = $callback;
		}

		return $this;
	}


	/**
	 * Registers run-time function.
	 */
	public function addFunction(string $name, callable $callback): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid function name '$name'.");
		}

		$this->functions->$name = $callback;
		return $this;
	}


	/**
	 * Call a run-time function.
	 * @param  mixed[]  $args
	 */
	public function invokeFunction(string $name, array $args): mixed
	{
		if (!isset($this->functions->$name)) {
			$hint = ($t = Helpers::getSuggestion(array_keys((array) $this->functions), $name))
				? ", did you mean '$t'?"
				: '.';
			throw new \LogicException("Function '$name' is not defined$hint");
		}

		return ($this->functions->$name)(...$args);
	}


	public function getFunctions(): array
	{
		return (array) $this->functions;
	}


	/**
	 * Adds new provider.
	 */
	public function addProvider(string $name, mixed $value): static
	{
		if (!preg_match('#^[a-z]\w*$#iD', $name)) {
			throw new \LogicException("Invalid provider name '$name'.");
		}

		$this->providers[$name] = $value;
		return $this;
	}


	/**
	 * Returns all providers.
	 * @return mixed[]
	 */
	public function getProviders(): array
	{
		return $this->providers;
	}


	public function setPolicy(?Policy $policy): static
	{
		$this->policy = $policy;
		return $this;
	}


	public function getPolicy(bool $effective = false): ?Policy
	{
		return !$effective || $this->sandboxed
			? $this->policy
			: null;
	}


	public function setExceptionHandler(callable $callback): static
	{
		$this->providers['coreExceptionHandler'] = $callback;
		return $this;
	}


	public function setSandboxMode(bool $on = true): static
	{
		$this->sandboxed = $on;
		return $this;
	}


	public function setContentType(string $type): static
	{
		$this->contentType = $type;
		return $this;
	}


	/**
	 * Sets path to temporary directory.
	 */
	public function setTempDirectory(?string $path): static
	{
		$this->tempDirectory = $path;
		return $this;
	}


	/**
	 * Sets auto-refresh mode.
	 */
	public function setAutoRefresh(bool $on = true): static
	{
		$this->autoRefresh = $on;
		return $this;
	}


	/**
	 * Enables declare(strict_types=1) in templates.
	 */
	public function setStrictTypes(bool $on = true): static
	{
		$this->strictTypes = $on;
		return $this;
	}


	protected function createLexer(): Compiler\TemplateLexer
	{
		return new Compiler\TemplateLexer;
	}


	protected function createParser(): Compiler\TemplateParser
	{
		return new Compiler\TemplateParser;
	}


	protected function createGenerator(): Compiler\TemplateGenerator
	{
		return new Compiler\TemplateGenerator;
	}


	public function setLoader(Loader $loader): static
	{
		$this->loader = $loader;
		return $this;
	}


	public function getLoader(): Loader
	{
		if (!$this->loader) {
			$this->loader = new Loaders\FileLoader;
		}

		return $this->loader;
	}


	/**
	 * @param  object|mixed[]  $params
	 * @return mixed[]
	 */
	private function processParams(object|array $params): array
	{
		if (is_array($params)) {
			return $params;
		} elseif (!is_object($params)) {
			throw new \InvalidArgumentException(sprintf('Engine::render() expects array|object, %s given.', gettype($params)));
		}

		$methods = (new \ReflectionClass($params))->getMethods(\ReflectionMethod::IS_PUBLIC);
		foreach ($methods as $method) {
			if ($method->getAttributes(Attributes\TemplateFilter::class)) {
				$this->addFilter($method->name, [$params, $method->name]);
			}

			if ($method->getAttributes(Attributes\TemplateFunction::class)) {
				$this->addFunction($method->name, [$params, $method->name]);
			}

			if (strpos((string) $method->getDocComment(), '@filter')) {
				trigger_error('Annotation @filter is deprecated, use attribute #[Latte\Attributes\TemplateFilter]', E_USER_DEPRECATED);
				$this->addFilter($method->name, [$params, $method->name]);
			}

			if (strpos((string) $method->getDocComment(), '@function')) {
				trigger_error('Annotation @function is deprecated, use attribute #[Latte\Attributes\TemplateFunction]', E_USER_DEPRECATED);
				$this->addFunction($method->name, [$params, $method->name]);
			}
		}

		return array_filter((array) $params, fn($key) => $key[0] !== "\0", ARRAY_FILTER_USE_KEY);
	}
}
