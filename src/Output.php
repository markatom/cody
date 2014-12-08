<?php

namespace Markatom\Cody;

use Nette\Object;

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

	/** @var Result[] */
	private $results = [];

	public function startProgress($totalCount)
	{
		$this->startedAt = microtime(TRUE);

		$this->totalCount = $totalCount;

		echo '  ';
	}

	public function advanceProgress(Result $result)
	{
		static $indicators = [
			Result::STATUS_OK            => ".",
			Result::STATUS_WARNINGS_ONLY => "W",
			Result::STATUS_VIOLATIONS    => "\033[1;37;41mV\033[0m"
		];

		echo $indicators[$result->getStatus()];

		$this->warningsCount += count($result->getWarnings());
		$this->violationsCount   += count($result->getViolations());

		$this->counter++;

		if ($this->counter % 50 === 0) {
			$this->writeProgressCounter();
			echo '  ';
		}

		if ($result->getStatus() !== Result::STATUS_OK) {
			$this->results[] = $result;
		}
	}

	public function finishProgress()
	{
		$this->runTime = microtime(TRUE) - $this->startedAt;

		echo str_repeat(' ', 50 - $this->counter % 50);

		$this->writeProgressCounter();

		echo PHP_EOL;
	}

	/**
	 * @param Result[] $results
	 */
	public function writeResults(array $results)
	{
		foreach ($results as $result) {
			if ($result->getViolations() || $result->getWarnings()) {

			}
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

}
