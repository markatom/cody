<?php

namespace Markatom\Cody\Utils;

use Nette\Object;
use Nette\Utils\Strings;

/**
 * Methods for checking naming conventions.
 * @author Tomáš Markacz
 */
class Naming extends Object
{

	const CAMEL_CASE_PATTERN = '~
		^
		[a-z]              # first word starts with lower case letter
		[0-9a-z]*          # inner letters and digits
		(
			(?<![0-9A-Z])  # not preceded by digit or upper case letter
			[A-Z]          # second and next word starts with upper case letter
			(?![0-9A-Z])   # not followed by digit or upper case letter
			[a-z0-9]*      # inner letters and digits
		)*
		$
	~x';

	const UNDERSCORE_PREFIXED_CAMEL_CASE_PATTERN = '~
		^
		_?                 # optional underscore prefix
		[a-z]              # first word starts with lower case letter
		[0-9a-z]*          # inner letters and digits
		(
			(?<![0-9A-Z])  # not preceded by digit or upper case letter
			[A-Z]          # second and next word starts with upper case letter
			(?![0-9A-Z])   # not followed by digit or upper case letter
			[a-z0-9]*      # inner letters and digits
		)*
		$
	~x';

	const PASCAL_CASE_PATTERN = '~
		^
		(
			(?<![A-Z])  # not preceded by upper case letter
			[A-Z]       # word starts with upper case letter
			(?![A-Z])   # not followed by upper case letter
			[a-z]*      # inner letters
			[0-9]*      # allow digits at the end of word
		)+
		$
	~x';

	const SCREAMING_SNAKE_CASE_PATTERN = '~
		^
		(
			(?<=[0-9A-Z])  # preceded by digit or upper case letter
			_              # word separator
			(?=[0-9A-Z])   # followed by digit or upper case letter
			| [0-9A-Z]     # or digit or upper case letter
		)+
		$
	~x';

	const UNDERSCORE_PREFIXED_SCREAMING_SNAKE_CASE_PATTERN = '~
		^
		_?                 # optional underscore prefix
		(
			(?<=[0-9A-Z])  # preceded by digit or upper case letter
			_              # word separator
			(?=[0-9A-Z])   # followed by digit or upper case letter
			| [0-9A-Z]     # or digit or upper case letter
		)+
		$
	~x';

    private function __construct() { } // static class

	/**
	 * @param string $string
	 * @return bool
	 */
	public static function isCamelCase($string)
	{
		return Strings::match($string, self::CAMEL_CASE_PATTERN) !== NULL;
	}

	/**
	 * @param string $string
	 * @return bool
	 */
	public static function isUnderscorePrefixedCamelCase($string)
	{
		return Strings::match($string, self::UNDERSCORE_PREFIXED_CAMEL_CASE_PATTERN) !== NULL;
	}

	/**
	 * @param string $string
	 * @return bool
	 */
	public static function isPascalCase($string)
	{
		return Strings::match($string, self::PASCAL_CASE_PATTERN) !== NULL;
	}

	/**
	 * @param string $string
	 * @return bool
	 */
	public static function isScreamingSnakeCase($string)
	{
		return Strings::match($string, self::SCREAMING_SNAKE_CASE_PATTERN) !== NULL;
	}

	/**
	 * @param string $string
	 * @return bool
	 */
	public static function isUnderscorePrefixedScreamingSnakeCase($string)
	{
		return Strings::match($string, self::UNDERSCORE_PREFIXED_SCREAMING_SNAKE_CASE_PATTERN) !== NULL;
	}

}
