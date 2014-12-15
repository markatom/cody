<?php

use Markatom\Cody\Output;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class OutputMock extends Output
{

	/** @var string */
	private $content = '';

	/**
	 * @param $string
	 */
	public function write($string)
	{
		$this->content .= $string;
    }

	/**
	 * @param $string
	 */
	public function writeLine($string)
	{
		$this->content .= $string . PHP_EOL;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

} 