<?php

namespace Foo;

/**
 * @author John Doe
 */
abstract class Bar
{

	/**
	 * @var string
	 */
	private $baz;

	/**
	 * Returns baz.
	 * @return string
	 */
	public abstract function getBaz();

	/**
	 * Does QUX!
	 */
	public function doQux()
	{
		echo 'QUX!';
	}

}
