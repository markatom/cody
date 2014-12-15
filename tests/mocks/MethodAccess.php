<?php

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
trait MethodAccess
{

	public function _getMethodReflection($name)
	{
		$thisClass   = new ReflectionClass($this);
		$mockedClass = $thisClass->getParentClass();

		$class = $thisClass;
		while ($class && !$class->hasMethod($name)) {
			$class = $class->getParentClass();
		}

		if (!$class) {
			$class = $mockedClass
				? $mockedClass
				: $thisClass;
			throw new LogicException("Undefined method {$class->getName()}::$name.");
		}

		return $class->getMethod($name);
	}

	public function _callMethod($name, array $arguments = [])
	{
		$method = $this->_getMethodReflection($name);

		$method->setAccessible(TRUE);
		$return = $method->invokeArgs($this, $arguments);
		$method->setAccessible(FALSE);

		return $return;
	}

}
