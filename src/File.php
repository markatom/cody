<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read string $path
 * @property-read bool $readOnly
 * @property-read string $content
 * @property-read string $originalContent
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

	/** @var string */
	private $originalContent;

	/** @var int */
	private $length;

	/** @var Mark[] */
	private $marks = [];

	/** @var bool */
	private $marksSorted = TRUE;

	/** @var int */
	private $errorsCount = 0;

	/** @var int */
	private $warningsCount = 0;

	/**
	 * @param string $content
	 * @param string $path
	 * @param bool $readOnly
	 */
	public function __construct($content, $path, $readOnly)
	{
		$this->content         = $content;
		$this->path            = $path;
		$this->readOnly        = $readOnly;
		$this->originalContent = $content;
		$this->length          = strlen($this->content);
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
	public function isReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * @return string
	 */
	public function getOriginalContent()
	{
		return $this->originalContent;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $modification
	 */
	public function modifyContent($offset, $length, $modification)
	{
		if ($offset < 0 || $offset > $this->length) { // same offset as length allows appending
			throw new InvalidOffsetException('Invalid offset given.');
		}

		if ($length < 0 || $offset + $length > $this->length) {
			throw new InvalidLengthException('Invalid length given.');
		}

		$prev = substr($this->content, 0, $offset);
		$next = substr($this->content, $offset + $length);

		$this->content = $prev . $modification . $next;

		$this->length += strlen($modification) - $length;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addWarning($offset, $length, $message)
	{
		if ($offset >= $this->length) {
			throw new InvalidOffsetException('Given offset is out of file content.');
		}

		if ($offset +  $length >= $this->length) {
			throw new InvalidLengthException('Given length with specified offset is out of file content.');
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
		if ($offset >= $this->length) {
			throw new InvalidOffsetException('Given offset is out of file content.');
		}

		if ($offset +  $length >= $this->length) {
			throw new InvalidLengthException('Given length with specified offset is out of file content.');
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
			$this->marksSorted = TRUE;
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
	}

}
