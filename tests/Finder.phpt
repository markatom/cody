<?php

use Markatom\Cody\Finder;
use Nette\Utils\FileSystem;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/mocks/ConfigurationMock.php';

$structure = [
	'dir' => [
		'a.php',
		'b.php',
		'c.txt',
		'd.ignore.php',
	],
	'greek' => [
		'alphaPattern.php',
		'betaPattern.php',
		'gamma.php',
		'delta.jpg',
	],
	'concat' => [
		'foo.php',
		'foobar.php',
		'foobarbaz.php',
		'foobarbazqux.php'
	],
	'foo' => [
		'bar' => [
			'baz' => [
				'qux.php',
			],
			'qux.php',
		],
		'qux.php',
	],
	'not' => [
		'from' => [
			'root.php',
		],
		'dir.php',
		'inRoot.php'
	],
	'latin' => [
		'lorem' => [
			'ipsum' => [
				'dolor.php',
			],
		],
		'sit.css',
	],
	'from' => [
		'root.php',
	],
	'return' => [
		'all' => [
			'by3' => [
				'3.phpt',
				'6.phpt',
				'9.phpt',
			],
			'by7' => [
				'7.phpt',
				'14.phpt',
				'21.phpt',
			],
		],
		'none' => [
			'by2' => [
				'2.phpt',
				'4.phpt',
				'6.phpt',
			],
			'by5' => [
				'5.phpt',
				'10.phpt',
				'15.phpt',
			],
		],
	],
	'dir.php' => [
		'a.php',
	],
	'inRoot.php',
];

$testDir = __DIR__ . '/../temp/tests/Files';
FileSystem::delete($testDir);
FileSystem::createDir($testDir);
chdir($testDir);

function buildStructure(array $dir, $prefix = '') {
	foreach ($dir as $key => $entry) {
		if (is_array($entry)) {
			$path = $prefix . '/' . $key;
			mkdir($path);
			buildStructure($entry, $path);

		} else {
			touch($prefix . '/' . $entry);
		}
	}
}

buildStructure($structure, getcwd());

$configuration = ConfigurationMock::_createWithoutConstructor()
	->_setProperty('configuration', [
		'sources' => [
			'dir/',
			'!*.ignore.php',
			'dir.php/',
			'*Pattern.php',
			'foo*qux.php',
			'foo/**/qux.php',
			'**/ipsum/dolor.php',
			'from/root.php',
			'/inRoot.php',
			'return/all/**',
		],
		'extensions' => [
			'php',
			'phpt',
		],
	]);

$files = new Finder($configuration);

$actual = $files->getFiles();
sort($actual);

Assert::equal([
	'/concat/foobarbazqux.php',
	'/dir.php/a.php',
	'/dir/a.php',
	'/dir/b.php',
	'/foo/bar/baz/qux.php',
	'/foo/bar/qux.php',
	'/foo/qux.php',
	'/from/root.php',
	'/greek/alphaPattern.php',
	'/greek/betaPattern.php',
	'/inRoot.php',
	'/latin/lorem/ipsum/dolor.php',
	'/return/all/by3/3.phpt',
	'/return/all/by3/6.phpt',
	'/return/all/by3/9.phpt',
	'/return/all/by7/14.phpt',
	'/return/all/by7/21.phpt',
	'/return/all/by7/7.phpt',
], $actual);

do { // remove empty directories
	FileSystem::delete($testDir);
	$testDir = substr($testDir, 0, strrpos($testDir, '/')); // go one level up
} while (count(glob($testDir . '/*')) === 0); // while empty directory
