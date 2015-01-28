<?php

namespace Markatom\Cody;

use Nette\Neon\Neon;
use Nette\Object;
use Nette\Utils\Arrays;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read array $extensions
 * @property-read array $sources
 * @property-read array $preprocessors
 * @property-read array $watchers
 */
class Configuration extends Object
{

	/**
	 * @var array
	 */
	private $configuration = [];

	/**
	 * @var array
	 */
	private static $defaults = [
		'extensions'    => ['php'],
		'sources'       => ['*'],
		'preprocessors' => [],
		'watchers'      => []
	];

	/**
	 * @param File $file
	 */
    public function __construct(File $file)
    {
        $configuration = (array) Neon::decode($file->content);

		foreach (self::$defaults as $key => $value) {
			if (!isset($configuration[$key])) {
				$configuration[$key] = $value;
			}
		}

		$configuration['watchers'] = $this->parseWatchers($configuration['watchers']);

		$this->configuration = $configuration;
	}

	/**
	 * @return array
	 */
	public function getExtensions()
	{
		return $this->configuration['extensions'];
	}

	/**
	 * @return array
	 */
	public function getSources()
	{
		return $this->configuration['sources'];
	}

	/**
	 * @return array
	 */
	public function getPreprocessors()
	{
		return $this->configuration['preprocessors'];
	}

	/**
	 * @return array
	 */
	public function getWatchers()
	{
		return array_keys($this->configuration['watchers']);
	}

	/**
	 * @param string $watcher
	 * @param array $defaults
	 * @return array
	 */
	public function getWatcherOptions($watcher, array $defaults = [])
	{
		try {
			return $this->mergeOptions($this->configuration['watchers'][$watcher], $defaults, []);

		} catch (OptionException $e) {
			$e->watcher = $watcher;
			throw $e;
		}
	}

	/**
	 * @param array $watchers
	 * @return array
	 */
	private function parseWatchers(array $watchers)
	{
		$parsed = [];

		foreach ($watchers as $class => $options) {
			if (is_int($class)) {
				$class     = $options;
				$options = [];
			}
			$parsed[$class] = $options;
		}

		return $parsed;
	}

	/**
	 * @param array $given
	 * @param array $defaults
	 * @param array $path
	 * @return array
	 */
	private function mergeOptions(array $given, array $defaults, array $path)
	{
		foreach ($defaults as $key => $item) {
			$itemPath   = $path;
			$itemPath[] = $key;

			try {
				if ($item instanceof Option) {
					$given[$key] = isset($given[$key])
						? $item->getValue($given[$key])
						: $item->getValue();

				} else {
					if (isset($given[$key])) {
						if (is_array($item) && is_array($given[$key])) {
							$given[$key] = $this->mergeOptions($given[$key], $item, $itemPath);
						}

					} else {
						$given[$key] = $item;
					}
				}

			} catch (OptionException $e) {
				$e->option = implode('.', $itemPath);
				throw $e;
			}
		}

		return $given;
	}

}
