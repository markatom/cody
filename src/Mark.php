<?php

namespace Markatom\Cody;

use Markatom\Cody\Utils\Whitespace;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read int $offset
 * @property-read int $line
 * @property-read int $column
 * @property-read int $length
 * @property-read int $type
 * @property-read string $text
 */
class Mark extends Object
{

	const TYPE_ERROR   = 1;
	const TYPE_WARNING = 2;

	const LAST_LINE_PATTERN = '~
		[^\n\r]*  # must not contain any line break characters
		$         # anchored to the end of string
	~xD'; // D modifier forces $ anchor to match only end of string

	/** @var int */
	private $offset;

	/** @var int */
	private $line;

	/** @var int */
	private $column;

	/** @var int */
	private $length;

	/** @var int */
	private $type;

	/** @var string */
	private $text;

	/**
	 * @param File $file
	 * @param int $offset
	 * @param int $length
	 * @param int $type
	 * @param string $text
	 */
    public function __construct(File $file, $offset, $length, $type, $text)
    {
		$this->offset = $offset;
		$this->length = $length;
		$this->type   = $type;
		$this->text   = $text;

		list ($this->line, $this->column) = $this->offsetToLineAndColumn($offset, $file);
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
	 * @return int
	 */
	public function getLine()
	{
		return $this->line;
	}

	/**
	 * @return int
	 */
	public function getColumn()
	{
		return $this->column;
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
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param int $offset
	 * @param File $file
	 * @return array
	 */
	private function offsetToLineAndColumn($offset, File $file)
	{
		$previous = (string) substr($file->content, 0, $offset); // force result to be string if an empty content given

		$line = Whitespace::countLineBreaks($previous) + 1;

		$matches = Strings::match($previous, self::LAST_LINE_PATTERN);
		$column  = strlen($matches[0]) + 1;

		return [$line, $column];
	}

}
