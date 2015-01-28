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
	private $startedAt;

	/** @var int */
	private $counter;

	/** @var int */
	private $errorsCount;

	/** @var int */
	private $totalCount;

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
		$this->startedAt  = microtime();
		$this->counter    = $this->errorsCount = 0;
		$this->totalCount = $totalCount;

		$this->output->write('  ');
	}

	/**
	 * @param SourceCode $source
	 */
	public function advance(SourceCode $source)
	{
		$mark = $source->getErrors()
			? "\033[1;37;41m!\033[0m"
			: '.';


		$this->output->write($mark);

		$this->errorsCount += count($source->getErrors());

		$this->counter++;

		if ($this->counter % 50 === 0) {
			$this->writeCounter();
			$this->output->write('  ');
		}
	}

	public function finish()
	{
		$this->runTime = microtime() - $this->startedAt;

		$this->output->write(str_repeat(' ', 50 - $this->counter % 50));

		$this->writeCounter();
	}

	/**
	 * @return int
	 */
	public function getErrorsCount()
	{
		return $this->errorsCount;
	}

	/**
	 * Returns run time in microseconds.
	 * @return int
	 */
	public function getRunTime()
	{
		return $this->runTime;
	}

	/**
	 */
	private function writeCounter()
	{
		$this->output->writeLine('  ' . $this->counter . '/' . $this->totalCount);
	}

}
