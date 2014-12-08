<?php

use Markatom\Cody\Configuration;
use Markatom\Cody\Option;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';

$configuration = new Configuration(__DIR__ . '/files/Configuration/empty.neon');

Assert::equal(['php'], $configuration->getExtensions());
Assert::equal(['*'], $configuration->getSources());
Assert::equal([], $configuration->getWatchers());

$configuration = new Configuration(__DIR__ . '/files/Configuration/test.neon');

Assert::equal(['php', 'phpt'], $configuration->getExtensions());
Assert::equal(['src', 'tests'], $configuration->getSources());
Assert::equal([
	'Foo\Bar\Baz\Qux',
	'Lorem\Ipsum',
	'Invalid\Bool\Value',
	'Invalid\Enum\Value',
	'Invalid\Int\Value',
],$configuration->getWatchers());

$expected = [
	'bool'   => TRUE,
	'enum'   => 'bar',
	'int'    => 42,
	'nested' => [
		'alpha' => 'omega',
		'beta'  => 'psi',
		'gamma' => 'chi',
	],
];

$defaults = [
	'bool'   => FALSE,
	'enum'   => Option::enum(['foo', 'bar', 'baz']),
	'int'    => 42,
	'nested' => [
		'gamma' => 'chi',
	],
];

Assert::equal($expected, $configuration->getWatcherOptions('Foo\Bar\Baz\Qux', $defaults));

Assert::equal([], $configuration->getWatcherOptions('Lorem\Ipsum'));

Assert::throws(function () use ($configuration) {
	$configuration->getWatcherOptions('Invalid\Bool\Value', [
		'bool' => Option::bool(),
	]);
}, 'Markatom\Cody\InvalidOption', 'Invalid value for option bool of watcher Invalid\Bool\Value, expected boolean.');

Assert::throws(function () use ($configuration) {
	$configuration->getWatcherOptions('Invalid\Enum\Value', [
		'nested'=> [
			'enum' => Option::enum(['foo', 'bar', 'baz']),
		],
	]);
}, 'Markatom\Cody\InvalidOption', 'Invalid value for option nested.enum of watcher Invalid\Enum\Value, expected one of [foo, bar, baz].');

Assert::throws(function () use ($configuration) {
	$configuration->getWatcherOptions('Invalid\Int\Value', [
		'deeply' => [
			'nested'=> [
				'int' => Option::int(),
			],
		],
	]);
}, 'Markatom\Cody\InvalidOption', 'Invalid value for option deeply.nested.int of watcher Invalid\Int\Value, expected integer.');
