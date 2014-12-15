<?php

use Markatom\Cody\Mark;

require_once __DIR__ . '/InstanceWithoutConstructor.php';
require_once __DIR__ . '/PropertyAccess.php';

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class MarkMock extends Mark
{

    use InstanceWithoutConstructor, PropertyAccess;

}
