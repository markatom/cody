<?php

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
trait InstanceWithoutConstructor
{

	/**
	 * @return $this
	 */
	public static function createWithoutConstructor()
	{
		$reflection = new ReflectionClass(get_called_class());

		return $reflection->newInstanceWithoutConstructor();
	}

}
