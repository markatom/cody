<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read string $path
 * @property-read bool $readOnly
 * @property string $content
 * @property-read Mark[] $marks
 * @property-read int $errorsCount
 * @property-read int $warningsCount
 */
class File extends Object
{

	/** @var string */
	private $path;

	/** @var bool */
	private $readOnly;

	/** @var string */
	private $content;

	/** @var int */
	private $contentLength;

	/** @var Mark[] */
	private $marks = [];

	/** @var bool */
	private $marksSorted = TRUE;

	/** @var int */
	private $errorsCount = 0;

	/** @var int */
	private $warningsCount = 0;

	/**
	 * @param string $path
	 * @param bool $readOnly
	 */
    public function __construct($path, $readOnly)
    {
		$this->path     = $path;
		$this->readOnly = $readOnly;

		$this->content = @file_get_contents($path); // intentionally @

		if ($this->content === FALSE) {
			throw new ReadException("Cannot read $path.");
		}

		$this->contentLength = strlen($this->content);
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return bool
	 */
	public function getReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content       = $content;
		$this->contentLength = strlen($content);
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addWarning($offset, $length, $message)
	{
		if ($offset >= $this->contentLength) {
			throw new InvalidOffsetException('Invalid offset given.');
		}

		$this->warningsCount++;
		$this->marksSorted = FALSE;

		$this->marks[] = new Mark($this, $offset, $length, Mark::TYPE_WARNING, $message);
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addError($offset, $length, $message)
	{
		if ($offset >= $this->contentLength) {
			throw new InvalidOffsetException('Invalid offset given.');
		}

		$this->errorsCount++;
		$this->marksSorted = FALSE;

		$this->marks[] = new Mark($this, $offset, $length, Mark::TYPE_ERROR, $message);
	}

	/**
	 * @return Mark[]
	 */
	public function getMarks()
	{
		if (!$this->marksSorted) {
			$this->sortMarks();
		}

		return $this->marks;
	}

	/**
	 * @return int
	 */
	public function getErrorsCount()
	{
		return $this->errorsCount;
	}

	/**
	 * @return int
	 */
	public function getWarningsCount()
	{
		return $this->warningsCount;
	}

	private function sortMarks()
	{
		usort($this->marks, function (Mark $a, Mark $b) {
			return $a->offset - $b->offset;
		});

		$this->marksSorted = TRUE;
	}

}
