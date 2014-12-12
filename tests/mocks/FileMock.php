<?php

use Markatom\Cody\File;

require_once __DIR__ . '/InstanceWithoutConstructor.php';
require_once __DIR__ . '/PropertyAccess.php';

/**
 * File mock with content setting ability.
 * @author Tomáš Markacz
 */
class FileMock extends File
{

	use InstanceWithoutConstructor, PropertyAccess;

	/**
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->_setProperty('content', $content);
	}

}
