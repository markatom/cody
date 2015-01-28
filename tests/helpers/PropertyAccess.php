<?php

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
trait PropertyAccess
{

	public function getPropertyReflection($name)
	{
		$thisClass   = new ReflectionClass($this);
		$mockedClass = $thisClass->getParentClass();

		$class = $thisClass;
		while ($class && !$class->hasProperty($name)) {
			$class = $class->getParentClass();
		}

		if (!$class) {
			$class = $mockedClass
				? $mockedClass
				: $thisClass;
			throw new LogicException("Undefined property {$class->getName()}::$$name.");
		}

		return $class->getProperty($name);
	}

	public function setProperty($name, $value)
	{
		$property = $this->getPropertyReflection($name);

		$property->setAccessible(TRUE);
		$property->setValue($this, $value);
		$property->setAccessible(FALSE);

		return $this;
	}

	public function getProperty($name)
	{
		$property = $this->getPropertyReflection($name);

		$property->setAccessible(TRUE);
		$value = $property->getValue($this);
		$property->setAccessible(FALSE);

		return $value;
	}

} 