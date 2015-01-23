<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Runner extends Object
{

	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * @var bool
	 */
	private $readOnly;

	/**
	 * @var Finder
	 */
	private $finder;

	/**
	 * @param Configuration $configuration
	 * @param bool $readOnly
	 */
    public function __construct(Configuration $configuration, $readOnly)
    {
		$this->configuration = $configuration;
		$this->readOnly      = $readOnly;
		$this->finder        = new Finder;
    }

	public function run()
	{
		$output = new Output(STDOUT, TRUE); // todo: formatting

		$files = $this->finder->findFiles($this->configuration->extensions, $this->configuration->sources);

		$progress = new Progress($output);

		$progress->start(count($files));

		foreach ($files as $file) {
			$content   = file_get_contents($file);
			$file      = new File($content, $file, $this->readOnly);
			$tokenizer = new Tokenizer($file);

			foreach ($this->configuration->watchers as $watcher) {
				if (!class_exists($watcher)) {
					throw new WatcherNotFoundException($watcher);
				}

				if (!$watcher instanceof Watcher) {
					throw new InvalidWatcherException($watcher);
				}

				/** @var Watcher $watcher */
				$watcher = new $watcher($file, $this->configuration->getWatcherOptions($watcher), $watcher->definedOptions());

				foreach ($watcher->watchedTokens as $token => $method) {
					$tokenizer->reset();

					try {
						while (TRUE) {
							$tokenizer->moveNext($token);
							$watcher->$method($tokenizer);
						}
					} catch (InvalidMoveException $e) { }
				}

				$progress->advance($file);
			}
		}

		$progress->finish();
	}

}
