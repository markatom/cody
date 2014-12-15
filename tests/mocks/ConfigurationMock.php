<?php

use Markatom\Cody\Configuration;

require_once __DIR__ . '/InstanceWithoutConstructor.php';
require_once __DIR__ . '/PropertyAccess.php';

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class ConfigurationMock extends Configuration
{

    use InstanceWithoutConstructor, PropertyAccess;

}
