<?php

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
trait PropertyAccess
{

	public function _getPropertyReflection($name)
	{
		$class = new ReflectionClass($this);

		while ($class && !$class->hasProperty($name)) {
			$class = $class->getParentClass();
		}

		if (!$class) {
			throw new LogicException("Undefined property {$class->getName()}::$$name.");
		}

		return $class->getProperty($name);
	}

    public function _setProperty($name, $value)
	{
		$property = $this->_getPropertyReflection($name);

		$property->setAccessible(TRUE);
		$property->setValue($this, $value);
		$property->setAccessible(FALSE);
	}

	public function _getProperty($name)
	{
		$property = $this->_getPropertyReflection($name);

		$property->setAccessible(TRUE);
		$value = $property->getValue($this);
		$property->setAccessible(FALSE);

		return $value;
	}

} 