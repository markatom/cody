<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read int $offset
 * @property-read int $length
 * @property-read string $text
 */
class Marker extends Object
{

	/** @var int */
	private $offset;

	/** @var int */
	private $length;

	/** @var string */
	private $text;

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $text
	 */
    public function __construct($offset, $length, $text)
    {
		$this->offset = $offset;
		$this->length = $length;
		$this->text   = $text;
    }

	/**
	 * @return int
	 */
	public function getLength()
	{
		return $this->length;
	}

	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

}
