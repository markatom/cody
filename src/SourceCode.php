<?php

namespace Markatom\Cody;

use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read File $file
 * @property-read bool $readOnly
 * @property-read string $content
 * @property-read Error[] $errors
 */
class SourceCode extends Object
{

	/** @var File */
	private $file;

	/** @var bool */
	private $readOnly;

	/** @var string */
	private $content;

	/** @var int */
	private $length;

	/** @var Error[] */
	private $errors = [];

	/** @var bool */
	private $errorsSorted = TRUE;

	/**
	 * @param File $file
	 * @param bool $readOnly
	 */
	public function __construct(File $file, $readOnly)
	{
		$this->file     = $file;
		$this->readOnly = $readOnly;
		$this->content  = $file->content;
		$this->length   = Strings::length($this->content);
	}

	/**
	 * @return File
	 */
	public function getFile()
	{
		return $this->file;
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
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $modification
	 */
	public function modify($offset, $length, $modification)
	{
		if ($offset < 0 || $offset > $this->length) { // same offset as length allows appending
			throw new InvalidOffsetException('Invalid offset given.');
		}

		if ($length < 0 || $offset + $length > $this->length) {
			throw new InvalidLengthException('Invalid length given.');
		}

		$prev = Strings::substring($this->content, 0, $offset);
		$next = Strings::substring($this->content, $offset + $length);

		$this->content = $prev . $modification . $next;
		$this->length += strlen($modification) - $length;
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

		if ($offset + $length >= $this->length) {
			throw new InvalidLengthException('Given length with specified offset is out of file content.');
		}

		$this->errors[]     = new Error($this, $offset, $length, $message);
		$this->errorsSorted = FALSE;
	}

	/**
	 * @return Error[]
	 */
	public function getErrors()
	{
		if (!$this->errorsSorted) {
			$this->sortErrors();
			$this->errorsSorted = TRUE;
		}

		return $this->errors;
	}

	private function sortErrors()
	{
		usort($this->errors, function (Error $a, Error $b) {
			return $a->offset - $b->offset;
		});
	}

}
