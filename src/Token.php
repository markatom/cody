<?php


namespace Markatom\Cody;

use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read int $offset
 * @property-read int $type
 * @property-read string $content
 * @property-read int $length
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
	 * @var int
	 */
	private $length;

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
		$this->length  = strlen($content);
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

	/**
	 * @return int
	 */
	public function getLength()
	{
		return $this->length;
	}

	/**
	 * @param array|int|string $criteria
	 * @return bool
	 */
	public function is($criteria)
	{
		if (!is_array($criteria)) {
			$criteria = [$criteria];
		}

		foreach ($criteria as $item) {
			if (is_int($item)) {
				if ($this->type === $item) {
					return TRUE;
				}

			} else {
				if (Strings::lower($this->content) === Strings::lower($item)) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}

}
