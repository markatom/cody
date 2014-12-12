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
 */
class File extends Object
{

	/** @var string */
	private $path;

	/** @var bool */
	private $readOnly;

	/** @var string */
	private $content;

	/** @var Marker[] */
	private $warnings = [];

	/** @var Marker[] */
	private $errors = [];

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
		$this->content = $content;
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addWarning($offset, $length, $message)
	{
		$this->warnings[] = new Marker($offset, $length, $message);
	}

	/**
	 * @param int $offset
	 * @param int $length
	 * @param string $message
	 */
	public function addError($offset, $length, $message)
	{
		$this->errors[] = new Marker($offset, $length, $message);
	}

	/**
	 * @return Marker[]
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return Marker[]
	 */
	public function getWarnings()
	{
		return $this->warnings;
	}

}
