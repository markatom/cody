<?php

namespace Markatom\Cody;

use Nette\Object;
use Nette\Utils\Finder;

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
	 * @var Scanner
	 */
	private $scanner;

	/**
	 * @param Configuration $configuration
	 * @param bool $readOnly
	 */
    public function __construct(Configuration $configuration, $readOnly)
    {
		$this->configuration = $configuration;
		$this->readOnly      = $readOnly;
		$this->scanner       = new Scanner($configuration);
    }

	public function run()
	{
		$output = new Output;

		$files = $this->scanner->getFiles();

		$output->startProgress(count($files));

		foreach ($files as $file) {
			$source = file_get_contents($file);

			$tokenizer = new Tokenizer($source, $this->readOnly);

			foreach ($this->configuration->getWatchers() as $watcher) {
				if (!class_exists($watcher)) {
					throw new WatcherNotFoundException($watcher);
				}

				if (!$watcher instanceof Watcher) {
					throw new InvalidWatcherException($watcher);
				}

				/** @var Watcher $watcher */
				$watcher = new $watcher($file, $this->configuration->getWatcherOptions($watcher), $this->readOnly);

				foreach ($watcher->getWatchedTokens() as $token => $method) {
					$tokenizer->reset();

					while (TRUE) {
						try {
							$tokenizer->moveNext($token);

							$watcher->$method($tokenizer);

						} catch (InvalidMoveException $e) {
							break;
						}
					}
				}

				$result = $watcher->getResult();

				$output->advanceProgress($result);
			}
		}

		$output->finishProgress();
	}

}
