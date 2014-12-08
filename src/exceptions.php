<?php

namespace Markatom\Cody;

use LogicException;
use RuntimeException;

class ConfigurationFileNotFoundException extends LogicException { }

class WatcherNotFoundException extends LogicException { }

class InvalidWatcherException extends LogicException { }

class InvalidMoveException extends RuntimeException { }

class ReadOnlyException extends LogicException { }

class InvalidValue extends LogicException { }

class RequiredValue extends LogicException { }

class InvalidOption extends LogicException { }

class RequiredOption extends LogicException { }
