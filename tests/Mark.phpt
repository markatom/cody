<?php

use Markatom\Cody\Mark;
use Markatom\Cody\Utils\Position;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/mocks/FileMock.php';

$lines = [
	'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
	'Fusce ante ipsum, pellentesque vel lectus vel, volutpat sollicitudin metus.',
	'Nam mollis placerat erat in iaculis.',
	'Pellentesque velit ipsum, placerat at iaculis sit amet, mollis sed sapien.',
];

$lineBreaks = [
	"\n", // Unix-like
	"\r", // old OS X
	"\r\n", // Windows
];

$tests = [
	[
		'offset'         => 0,
		'windowsOffset'  => 0,
		'expectedLine'   => 1,
		'expectedColumn' => 1,
	],
	[
		'offset'         => 92,
		'windowsOffset'  => 93,
		'expectedLine'   => 2,
		'expectedColumn' => 36,
	],
	[
		'offset'         => 169,
		'windowsOffset'  => 171,
		'expectedLine'   => 3,
		'expectedColumn' => 37,
	],
	[
		'offset'         => 170,
		'windowsOffset'  => 173,
		'expectedLine'   => 4,
		'expectedColumn' => 1,
	],
	[
		'offset'         => 244,
		'windowsOffset'  => 247,
		'expectedLine'   => 4,
		'expectedColumn' => 75,
	],
];

foreach ($lineBreaks as $lineBreak) {
	$file = FileMock::_createWithoutConstructor();

	$file->setContent(implode($lineBreak, $lines));

	foreach ($tests as $test) {
		$offset = $lineBreak === "\r\n" // windows
			? $test['windowsOffset']
			: $test['offset'];

		$mark = new Mark($file, $offset, 0, 0, '');

		Assert::equal($test['expectedLine'], $mark->getLine());
		Assert::equal($test['expectedColumn'], $mark->getColumn());
	}
}
