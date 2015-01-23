<?php

use Markatom\Cody\File;
use Markatom\Cody\Mark;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';

$content = 'Foo';

$file = new File($content, 'dummy', FALSE);

Assert::throws(function () use ($file) {
	$file->addError(42, 8, 'The answer to the ultimate question of life the universe and everything');
}, 'Markatom\Cody\InvalidOffsetException');

Assert::throws(function () use ($file) {
	$file->addWarning(2, 4, 'Foo bar baz qux');
}, 'Markatom\Cody\InvalidLengthException');

Assert::equal([], $file->getMarks());

$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin non viverra arcu. Morbi vitae tincidunt tell'
	. ' at sapien euismod, sollicitudin orci eget, tempus sem. Suspendisse id tellus a ex fermentum sagittis. Proin'
	. ' enim dolor, vehicula maximus ante in, elementum congue eros. Fusce bibendum, mauris interdum semper posuere,';

$file = new File($content, 'dummy', FALSE);

$file->addError(42, 8, 'The answer to the ultimate question of life the universe and everything');
$file->addWarning(3, 4, 'Foo bar baz qux');
$file->addError(77, 2, 'Alpha beta gamma delta');
$file->addError(64, 3, 'Lorem ipsum');

$expected = [
	[
		'offset' => 3,
		'length' => 4,
		'type'   => Mark::TYPE_WARNING,
		'text'   => 'Foo bar baz qux',
	],
	[
		'offset' => 42,
		'length' => 8,
		'type'   => Mark::TYPE_ERROR,
		'text'   => 'The answer to the ultimate question of life the universe and everything'
	],
	[
		'offset' => 64,
		'length' => 3,
		'type'   => Mark::TYPE_ERROR,
		'text'   => 'Lorem ipsum'
	],
	[
		'offset' => 77,
		'length' => 2,
		'type'   => Mark::TYPE_ERROR,
		'text'   => 'Alpha beta gamma delta'
	],
];

foreach ($file->getMarks() as $index => $mark) {
	Assert::same($expected[$index]['offset'], $mark->offset);
	Assert::same($expected[$index]['length'], $mark->length);
	Assert::same($expected[$index]['type'], $mark->type);
	Assert::same($expected[$index]['text'], $mark->text);
}
