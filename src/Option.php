<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Option extends Object
{

	const BOOL = 'bool';
	const ENUM = 'enum';
	const INT  = 'int';

	private $type;

	private $values;

	private $default;

    private function __construct() { } // use factory methods

	/**
	 * @param bool $default
	 * @return Option
	 */
	public static function bool($default = NULL)
	{
		$instance = new self;

		$instance->type    = self::BOOL;
		$instance->default = $default;

		return $instance;
	}

	/**
	 * @param array $values
	 * @param string $default
	 * @return Option
	 */
	public static function enum(array $values, $default = NULL)
	{
		$instance = new self;

		$instance->type    = self::ENUM;
		$instance->values  = $values;
		$instance->default = $default;

		return $instance;
	}

	/**
	 * @param int $default
	 * @return Option
	 */
	public static function int($default = NULL)
	{
		$instance = new self;

		$instance->type    = self::INT;
		$instance->default = $default;

		return $instance;
	}

	/**
	 * @param mixed $given
	 * @return mixed
	 */
	public function getValue($given = NULL)
	{
		if ($given === NULL) {
			if ($this->default === NULL) {
				throw new RequiredValueException();
			}

			return $this->default;
		}

		switch ($this->type) {
			case self::BOOL:
				if (!is_bool($given)) {
					throw new InvalidValueException('expected boolean');
				}

				break;

			case self::ENUM:
				if (!is_string($given)) {
					throw new InvalidValueException('expected string');
				}

				if (!in_array($given, $this->values)) {
					throw new InvalidValueException('expected one of [' . implode(', ', $this->values) . ']');
				}

				break;

			case self::INT:
				if (!is_int($given)) {
					throw new InvalidValueException('expected integer');
				}

				break;
		}

		return $given;
	}

}
