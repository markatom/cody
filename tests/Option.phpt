<?php

use Markatom\Cody\Option;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';

// bool

Assert::equal(TRUE, Option::bool(TRUE)->getValue());
Assert::equal(TRUE, Option::bool(FALSE)->getValue(TRUE));

Assert::throws(function () {
	Option::bool()->getValue();
}, 'Markatom\Cody\RequiredValue');

Assert::throws(function () {
	Option::bool()->getValue(42);
}, 'Markatom\Cody\InvalidValue');

// enum

Assert::equal('bar', Option::enum(['foo', 'bar', 'baz'], 'bar')->getValue());
Assert::equal('foo', Option::enum(['foo', 'bar', 'baz'], 'bar')->getValue('foo'));

Assert::throws(function () {
	Option::enum(['foo', 'bar', 'baz'])->getValue();
}, 'Markatom\Cody\RequiredValue');

Assert::throws(function () {
	Option::enum(['foo', 'bar', 'baz'])->getValue('qux');
}, 'Markatom\Cody\InvalidValue');

Assert::throws(function () {
	Option::enum(['foo', 'bar', 'baz'])->getValue(TRUE);
}, 'Markatom\Cody\InvalidValue');

// int

Assert::equal(42, Option::int(42)->getValue());
Assert::equal(666, Option::int(42)->getValue(666));

Assert::throws(function () {
	Option::int()->getValue();
}, 'Markatom\Cody\RequiredValue');

Assert::throws(function () {
	Option::int()->getValue('foo');
}, 'Markatom\Cody\InvalidValue');
