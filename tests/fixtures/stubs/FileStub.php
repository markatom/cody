<?php

namespace Testing\Fixtures\Stubs;

use Markatom\Cody\Fileable;
use Markatom\Cody\Error;
use Nette\Object;
use Testing\NotImplementedException;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class FileStub extends Object implements Fileable
{

	/** @var string */
	private $path;

	/** @var Error[] */
	private $marks;

	public function __construct($path, array $marks = [])
	{
		$this->path  = $path;
		$this->marks = $marks;
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
		throw new NotImplementedException;
	}

	/**
	 * @return string
	 */
	public function getOriginalContent()
	{
		throw new NotImplementedException;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		throw new NotImplementedException;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $modification
	 */
	public function modifyContent($offset, $length, $modification)
	{
		throw new NotImplementedException;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addWarning($offset, $length, $message)
	{
		throw new NotImplementedException;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addError($offset, $length, $message)
	{
		throw new NotImplementedException;
	}

	/**
	 * @return Error[]
	 */
	public function getMarks()
	{
		return $this->marks;
	}

	/**
	 * @return int
	 */
	public function getErrorsCount()
	{
		throw new NotImplementedException;
	}

	/**
	 * @return int
	 */
	public function getWarningsCount()
	{
		throw new NotImplementedException;
	}

}
