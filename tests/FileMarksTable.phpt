<?php

use Markatom\Cody\FileMarksTable;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/mocks/FileMock.php';
require_once __DIR__ . '/mocks/MarkMock.php';
require_once __DIR__ . '/mocks/OutputMock.php';

$output = new OutputMock();

$file = FileMock::_createWithoutConstructor()
	->_setProperty('path', 'App/Model/Service/FooBarService.php')
	->_setProperty('marksSorted', FALSE)
	->_setProperty('marks', [
		MarkMock::_createWithoutConstructor()
			->_setProperty('offset', 1)
			->_setProperty('line', 1)
			->_setProperty('column', 1)
			->_setProperty('type', MarkMock::TYPE_ERROR)
			->_setProperty('text', 'Foo bar baz qux'),
		MarkMock::_createWithoutConstructor()
			->_setProperty('offset', 5293)
			->_setProperty('line', 259)
			->_setProperty('column', 22)
			->_setProperty('type', MarkMock::TYPE_ERROR)
			->_setProperty('text', 'Alpha beta gamma delta epsilon zeta'),
		MarkMock::_createWithoutConstructor()
			->_setProperty('offset', 728)
			->_setProperty('line', 32)
			->_setProperty('column', 10)
			->_setProperty('type', MarkMock::TYPE_ERROR)
			->_setProperty('text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer malesuada ante'
				. ' magna, ac suscipit velit viverra sed'),
	]);

$table = new FileMarksTable($output, $file);

$table->render();

Assert::equal(<<<END
Details for App/Model/Service/FooBarService.php:
ERROR   1: 1 Foo bar baz qux
ERROR  32:10 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
             malesuada ante magna, ac suscipit velit viverra sed
ERROR 259:22 Alpha beta gamma delta epsilon zeta

END
, $output->getContent());

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$output = new OutputMock();

$file = FileMock::_createWithoutConstructor()
	->_setProperty('path', 'tests/App/Presenter/UserPresenter.phpt')
	->_setProperty('marksSorted', FALSE)
	->_setProperty('marks', [
		MarkMock::_createWithoutConstructor()
			->_setProperty('offset', 912)
			->_setProperty('line', 42)
			->_setProperty('column', 20)
			->_setProperty('type', MarkMock::TYPE_WARNING)
			->_setProperty('text', 'The answer to the ultimate question of life the universe and everything'),
		MarkMock::_createWithoutConstructor()
			->_setProperty('offset', 1129)
			->_setProperty('line', 50)
			->_setProperty('column', 66)
			->_setProperty('type', MarkMock::TYPE_ERROR)
			->_setProperty('text',
				'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu'
					. ' is the Maori name for a hill, 305 metres high, close to Porangahau, south of Waipukurau in'
					. ' southern Hawke\'s Bay, New Zealand.'),
	]);

$table = new FileMarksTable($output, $file);

$table->render();

Assert::equal(<<<END
Details for tests/App/Presenter/UserPresenter.phpt:
WARNING 42:20 The answer to the ultimate question of life the universe and
              everything
ERROR   50:66 Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupo
              kaiwhenuakitanatahu is the Maori name for a hill, 305 metres high,
              close to Porangahau, south of Waipukurau in southern Hawke's Bay,
              New Zealand.

END
, $output->getContent());
