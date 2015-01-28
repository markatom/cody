<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Runner extends Object
{

	/** @var Configuration */
	private $configuration;

	/** @var Finder */
	private $finder;

	/** @var Output */
	private $output;

	/** @var bool */
	private $readOnly;

	/**
	 * @param Configuration $configuration
	 * @param Finder $finder
	 * @param Output $output
	 * @param bool $readOnly
	 */
    public function __construct(Configuration $configuration, Finder $finder, Output $output, $readOnly)
    {
		$this->configuration = $configuration;
		$this->finder        = $finder;
		$this->output        = $output;
		$this->readOnly      = $readOnly;
    }

	public function run()
	{
		$files = $this->finder->findFiles($this->configuration->extensions, $this->configuration->sources);

		$progress = new Progress($this->output);

		$progress->start(count($files));

		$erroneous = [];
		foreach ($files as $file) {
			$file       = new File($file);
			$sourceCode = new SourceCode($file, $this->readOnly);
			$tokenizer  = new Tokenizer($sourceCode);

			foreach ($this->configuration->watchers as $watcher) {
				if (!class_exists($watcher)) {
					throw new WatcherNotFoundException($watcher);
				}

//				if (!$watcher instanceof Watcher) {
//					throw new InvalidWatcherException($watcher);
//				}

				/** @var Watcher $watcher */
				$watcher = new $watcher($sourceCode, $this->configuration->getWatcherOptions($watcher, $watcher::getDefinedOptions()), FALSE);

				foreach ($watcher->watchedTokens as $method => $tokens) {
					$tokenizer->first();

					while ($tokenizer->next($tokens)) {
						$watcher->setTokenizer(clone $tokenizer);
						$watcher->$method();
					}
				}
			}

			$progress->advance($sourceCode);

			if ($sourceCode->getErrors()) {
				$erroneous[] = $sourceCode;
			}
		}

		$progress->finish();

		$this->output->writeBlankLine();

		foreach ($erroneous as $sourceCode) {
			$table = new Table($this->output, $sourceCode);

			$table->render();

			$this->output->writeBlankLine();
		}
	}

}
