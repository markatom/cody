<?php

use Markatom\Cody\Error;

require_once __DIR__ . '/../helpers/InstanceWithoutConstructor.php';
require_once __DIR__ . '/../helpers/PropertyAccess.php';

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class _ErrorStub extends Error
{

    use InstanceWithoutConstructor, PropertyAccess;

}
