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
		(?<!\r)\n  # line feed not preceded by carriage return (Unix-like)
		| \r       # or carriage return (old OS X)
		| \r\n     # or line feed with carriage return (Windows)
	~x';

    private function __construct() { } // static class

	/**
	 * Counts number of line breaks in string.
	 * Unix-like, old OS X and Windows line breaks supported.
	 * @param string $whitespace
	 * @return int
	 */
	public static function countLineBreaks($whitespace)
	{
		return count(Strings::matchAll($whitespace, self::LINE_BREAK_PATTERN));
	}

}
