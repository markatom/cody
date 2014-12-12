<?php

namespace Markatom\Cody\Utils;

use Markatom\Cody\File;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Position extends Object
{

	const LAST_LINE_PATTERN = '~
		[^\n\r]*  # must not contain any line break characters
		$         # anchored to the end of string
	~xD'; // D modifier forces $ anchor to match only end of string

    private function __construct() { } // static class

	/**
	 * @param int $offset
	 * @param File $file
	 * @return array
	 */
	public static function offsetToLineAndColumn($offset, File $file)
	{
		$previous = (string) substr($file->content, 0, $offset); // force result to be string if an empty content given

		$line = Whitespace::countLineBreaks($previous) + 1;

		$matches = Strings::match($previous, self::LAST_LINE_PATTERN);
		$column  = strlen($matches[0]) + 1;

		return [$line, $column];
	}

}
