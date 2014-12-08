<?php


namespace Markatom\Cody;

use Nette\Object;

/**
 * @author Tomáš Markacz
 *
 * @property-read int $offset
 * @property-read int $type
 * @property-read string $content
 */
class Token extends Object
{
	/**
	 * @var int
	 */
	private $offset;

	/**
	 * @var int
	 */
	private $type;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @param int $offset
	 * @param int $type
	 * @param string $content
	 */
	public function __construct($offset, $type, $content)
	{
		$this->offset  = $offset;
		$this->type    = $type;
		$this->content = $content;
	}

	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

}
