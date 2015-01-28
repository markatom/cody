<?php

namespace Markatom\Cody;

use Markatom\Cody\Utils\Whitespace;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read SourceCode $source
 * @property-read int $offset
 * @property-read int $line
 * @property-read int $column
 * @property-read int $length
 * @property-read string $text
 * @property-read string $marked
 */
class Error extends Object
{

	const LAST_LINE_PATTERN = '~
		[^\n\r]*  # must not contain any line break characters
		$         # anchored to the end of a string
	~xD'; // D modifier forces $ to match only end of string

	/** @var SourceCode */
	private $source;

	/** @var int */
	private $offset;

	/** @var int */
	private $line;

	/** @var int */
	private $column;

	/** @var int */
	private $length;

	/** @var string */
	private $text;

	/** @var string */
	private $marked;

	/**
	 * @param SourceCode $source
	 * @param int $offset
	 * @param int $length
	 * @param string $text
	 */
    public function __construct(SourceCode $source, $offset, $length, $text)
    {
		$this->source = $source;
		$this->offset = $offset;
		$this->length = $length;
		$this->text   = $text;
		$this->marked = Strings::substring($source->content, $offset, $length);

		list ($this->line, $this->column) = $this->offsetToLineAndColumn($offset, $source);
    }

	/**
	 * @return SourceCode
	 */
	public function getSource()
	{
		return $this->source;
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
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	public function getMarked()
	{
		return $this->marked;
	}

	/**
	 * @param int $offset
	 * @param SourceCode $source
	 * @return array
	 */
	private function offsetToLineAndColumn($offset, SourceCode $source)
	{
		$previous = (string) substr($source->getContent(), 0, $offset); // force result to be string if an empty content given

		$line = Whitespace::countLineBreaks($previous) + 1;

		$matches = Strings::match($previous, self::LAST_LINE_PATTERN);
		$column  = strlen($matches[0]) + 1;

		return [$line, $column];
	}

}
