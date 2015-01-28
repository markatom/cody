<?php

namespace Markatom\Cody;

use LogicException;
use RuntimeException;

class ConfigurationFileNotFoundException extends LogicException { }

class WatcherNotFoundException extends LogicException { }

class InvalidWatcherException extends LogicException { }

class InvalidMoveException extends RuntimeException { }

class ReadOnlyException extends LogicException { }

/**
 * @property-write string $option
 * @property-write string $watcher
 */
abstract class OptionException extends LogicException
{

	/** @var string */
	protected $option;

	/**
	 * @param string $option
	 */
	public function setOption($option)
	{
		$this->option = $option;
	}

	/**
	 * @param string $watcher
	 * @return void
	 */
	abstract public function setWatcher($watcher);

}

/**
 * @property-write string $expected
 */
class InvalidOptionException extends OptionException
{

	/** @var string */
	protected $expected;

	/**
	 * @param string $expected
	 * @return InvalidOptionException
	 */
	public static function expected($expected)
	{
		$self = new self();

		$self->expected = $expected;

		return $self;
	}

	/**
	 * @param string $watcher
	 */
	public function setWatcher($watcher)
	{
		$this->message = "Invalid value for option $this->option of watcher $watcher, expected $this->expected.";
	}

}

class RequiredOptionException extends OptionException
{

	/**
	 * @param string $watcher
	 */
	public function setWatcher($watcher)
	{
		$this->message = "Required value for option $this->option of watcher $watcher.";
	}

}

class ReadException extends RuntimeException { }

class WriteException extends RuntimeException { }

class InvalidOffsetException extends LogicException { }

class InvalidLengthException extends LogicException { }

class InvalidArgumentException extends \InvalidArgumentException { }
