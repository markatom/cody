<?php

namespace Markatom\Cody;

use Markatom\Cody\Utils\Whitespace;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Output extends Object
{

	const HINT_FIX = 0;

	const HINT_SHOW = 1;

	private $totalCount;

	private $startedAt;

	private $runTime;

	private $counter = 0;

	private $warningsCount = 0;

	private $violationsCount = 0;

	/** @var File[] */
	private $files = [];

	public function startProgress($totalCount)
	{
		$this->startedAt = microtime(TRUE);

		$this->totalCount = $totalCount;

		echo '  ';
	}

	public function advanceProgress(File $file)
	{
		$errors   = count($file->getErrors());
		$warnings = count($file->getWarnings());

		echo $errors
			? "\033[1;37;41mE\033[0m"
			: ($warnings ? 'W' : '.');

		$this->warningsCount   += $warnings;
		$this->violationsCount += $errors;

		$this->counter++;

		if ($this->counter % 50 === 0) {
			$this->writeProgressCounter();
			echo '  ';
		}

		if ($errors || $warnings) {
			$this->files[] = $file;
		}
	}

	public function finishProgress()
	{
		$this->runTime = microtime(TRUE) - $this->startedAt;

		echo str_repeat(' ', 50 - $this->counter % 50);

		$this->writeProgressCounter();

		echo PHP_EOL;
	}

	public function writeResults()
	{
		foreach ($this->files as $file) {
			echo $file->getPath() . PHP_EOL;
		}
	}

	public function writeSummary()
	{
		$formatted = number_format($this->runTime, 3);

		echo "Checked $this->totalCount files in $formatted seconds." . PHP_EOL;
		echo "Found $this->warningsCount warnings and $this->violationsCount violations against coding standards." . PHP_EOL . PHP_EOL;
	}

	public function writeHints()
	{
		echo "Run fix command to fix warnings and violations automatically." . PHP_EOL;
		echo "Run with show option to show warnings and violations in source code." . PHP_EOL;
	}

	private function writeProgressCounter()
	{
		echo '  ' . $this->counter . '/' . $this->totalCount . PHP_EOL;
	}

	/**
	 * @param File $file
	 * @param Marker $marker
	 * @return array
	 */
	public function getMarkerPosition(Marker $marker, File $file)
	{

	}

}
