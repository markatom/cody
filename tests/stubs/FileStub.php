<?php

use Markatom\Cody\File;

require_once __DIR__ . '/../helpers/InstanceWithoutConstructor.php';
require_once __DIR__ . '/../helpers/PropertyAccess.php';

/**
 * File mock with content setting ability.
 * @author Tomáš Markacz
 */
class _FileStub extends File
{

	use InstanceWithoutConstructor, PropertyAccess;

	public function setContent($content)
	{
		$this->setProperty('content', $content);
		$this->setProperty('originalContent', $content);
		$this->setProperty('length', strlen($content));
	}

}
