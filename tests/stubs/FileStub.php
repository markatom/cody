<?php

use Markatom\Cody\SourceCode;

require_once __DIR__ . '/../helpers/InstanceWithoutConstructor.php';
require_once __DIR__ . '/../helpers/PropertyAccess.php';

/**
 * File mock with content setting ability.
 * @author Tomáš Markacz
 */
class _SourceCodeStub extends SourceCode
{

	use InstanceWithoutConstructor, PropertyAccess;

	public function setContent($content)
	{
		$this->setProperty('content', $content);
		$this->setProperty('originalContent', $content);
		$this->setProperty('length', strlen($content));
	}

}
