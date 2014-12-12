<?php

use Markatom\Cody\Marker;
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
		'offset'        => 0,
		'windowsOffset' => 0,
		'expected'      => [1, 1],
	],
	[
		'offset'        => 92,
		'windowsOffset' => 93,
		'expected'      => [2, 36],
	],
	[
		'offset'        => 169,
		'windowsOffset' => 171,
		'expected'      => [3, 37],
	],
	[
		'offset'        => 170,
		'windowsOffset' => 173,
		'expected'      => [4, 1],
	],
	[
		'offset'        => 244,
		'windowsOffset' => 247,
		'expected'      => [4, 75],
	],
];

foreach ($lineBreaks as $lineBreak) {
	$file = FileMock::_createWithoutConstructor();

	$file->setContent(implode($lineBreak, $lines));

	foreach ($tests as $test) {
		$offset = $lineBreak === "\r\n" // windows
			? $test['windowsOffset']
			: $test['offset'];

		Assert::equal($test['expected'], Position::offsetToLineAndColumn($offset, $file));
	}
}
