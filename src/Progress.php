<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Progress extends Object
{

	/** @var Output */
	private $output;

	/** @var int */
	private $started;

	/** @var int */
	private $counter;

	/** @var int */
	private $errors;

	/** @var int */
	private $warnings;

	/** @var int */
	private $total;

	/** @var int */
	private $runTime;

	/**
	 * @param Output $output
	 */
	public function __construct(Output $output)
	{
	    $this->output = $output;
	}

	/**
	 * @param int $totalCount
	 */
	public function start($totalCount)
	{
		$this->started = microtime();
		$this->counter = $this->warnings = $this->errors = 0;
		$this->total   = $totalCount;

		$this->output->write('  ');
	}

	/**
	 * @param File $file
	 */
	public function advance(File $file)
	{
		$mark = $file->errorsCount
			? "\033[1;37;41mE\033[0m"
			: ($file->warningsCount ? 'W' : '.');

		$this->output->write($mark);

		$this->warnings += $file->warningsCount;
		$this->errors   += $file->errorsCount;

		$this->counter++;

		if ($this->counter % 50 === 0) {
			$this->writeCounter();
			$this->output->write('  ');
		}
	}

	public function finish()
	{
		$this->runTime = microtime() - $this->started;

		$this->output->write(str_repeat(' ', 50 - $this->counter % 50));

		$this->writeCounter();
	}

	/**
	 * @return int
	 */
	public function getErrorsCount()
	{
		return $this->errors;
	}

	/**
	 * @return int
	 */
	public function getWarningsCount()
	{
		return $this->warnings;
	}

	/**
	 * Returns run time in microseconds.
	 * @return int
	 */
	public function getRunTime()
	{
		return $this->runTime;
	}

	private function writeCounter()
	{
		$this->output->writeLine('  ' . $this->counter . '/' . $this->total);
	}

}
