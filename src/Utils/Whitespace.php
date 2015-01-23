<?php

namespace Markatom\Cody\Utils;

use Nette\Object;
use Nette\Utils\Strings;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Whitespace extends Object
{

	const LINE_BREAK_PATTERN = '~
		\r\n  # line feed with carriage return (Windows), must be first, next patterns will not match
		| \n  # or line feed (Unix-like)
		| \r  # or carriage return (old OS X)
	~x';

	const LEADING_SPACES_PATTERN = '"~^ *~"';

    private function __construct() { } // static class

	/**
	 * Counts number of line breaks in string.
	 * Unix-like, old OS X and Windows line breaks supported.
	 * @param string $string
	 * @return int
	 */
	public static function countLineBreaks($string)
	{
		return count(Strings::matchAll($string, self::LINE_BREAK_PATTERN));
	}

	/**
	 * @param string $string
	 * @return string[]
	 */
	public static function toLines($string)
	{
		return preg_split(self::LINE_BREAK_PATTERN, $string);
	}

	/**
	 * @param string $string
	 * @param int $spaces
	 * @return string
	 */
	public static function expandTabs($string, $spaces = 4)
	{
		return str_replace("\t", str_repeat(' ', $spaces), $string);
	}

	/**
	 * @param string $string
	 * @return int
	 */
	public static function countLeadingSpaces($string)
	{
		return count(Strings::matchAll($string, self::LEADING_SPACES_PATTERN));
	}

}
