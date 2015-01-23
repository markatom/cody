<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * Output interface.
 * @author Tomáš Markacz
 */
class Output extends Object
{

	const OPEN_TAG_PATTERN  = '~<([^>])+>~';
	const CLOSE_TAG_PATTERN = '~</>~';

	/** @var resource */
	private $stream;

	/** @var bool */
	private $formatting;

	/**
	 * @param resource $stream
	 * @param bool $formatting
	 */
	public function __construct($stream, $formatting)
	{
		$this->stream     = $stream;
		$this->formatting = $formatting;
	}

	/**
	 * @param string $string
	 */
	public function write($string)
	{
		fwrite($this->stream, $string);
	}

	/**
	 * @param string $string
	 */
	public function writeLine($string)
	{
		$this->write($string . PHP_EOL);
	}

	private function format($string)
	{
		if (!$this->formatting) {
			return preg_replace([self::OPEN_TAG_PATTERN, self::CLOSE_TAG_PATTERN], '', $string);
		}

	}

}
