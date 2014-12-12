<?php

namespace Markatom\Cody;

use LogicException;
use RuntimeException;

class ConfigurationFileNotFoundException extends LogicException { }

class WatcherNotFoundException extends LogicException { }

class InvalidWatcherException extends LogicException { }

class InvalidMoveException extends RuntimeException { }

class ReadOnlyException extends LogicException { }

class InvalidValueException extends LogicException { }

class RequiredValueException extends LogicException { }

class InvalidOptionException extends LogicException { }

class RequiredOptionException extends LogicException { }

class ReadException extends RuntimeException { }
