<?php

use Tester\Assert;
use Markatom\Cody\InvalidOffsetException;
use Markatom\Cody\InvalidLengthException;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/stubs/FileStub.php';

$file = FileStub::createWithoutConstructor();

$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

$file->setContent($content);

$file->modifyContent(22, 0, 'quere '); // insert
Assert::same('Lorem ipsum dolor sit quere amet, consectetur adipiscing elit.', $file->getContent());

$file->modifyContent(46, 10, 'esit'); // replace
Assert::same('Lorem ipsum dolor sit quere amet, consectetur esit elit.', $file->getContent());

$file->modifyContent(0, 6, ''); // remove
Assert::same('ipsum dolor sit quere amet, consectetur esit elit.', $file->getContent());

$file->modifyContent(0, 0, 'Debere '); // prepend
Assert::same('Debere ipsum dolor sit quere amet, consectetur esit elit.', $file->getContent());

$file->modifyContent(57, 0, '..'); // append
Assert::same('Debere ipsum dolor sit quere amet, consectetur esit elit...', $file->getContent());

Assert::exception(function () use ($file) {
	$file->modifyContent(60, 0, '');
}, InvalidOffsetException::class);

Assert::exception(function () use ($file) {
	$file->modifyContent(-1, 0, '');
}, InvalidOffsetException::class);

Assert::exception(function () use ($file) {
	$file->modifyContent(59, 1, '');
}, InvalidLengthException::class);

Assert::exception(function () use ($file) {
	$file->modifyContent(7, -1, '');
}, InvalidLengthException::class);
