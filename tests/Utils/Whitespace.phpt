<?php

use Markatom\Cody\Utils\Whitespace;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

Assert::equal(1, Whitespace::countLineBreaks("foo\nbar")); // Unix-like
Assert::equal(2, Whitespace::countLineBreaks("lorem\ripsum\rdolor")); // old OS X
Assert::equal(3, Whitespace::countLineBreaks("alpha\r\nbeta\r\ngamma\r\ndelta")); // Windows
