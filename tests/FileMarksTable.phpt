<?php

use Markatom\Cody\Table;
use Tester\Assert;
use Testing\Fixtures\Stubs\OutputStub;

require_once __DIR__ . '/bootstrap.php';

$output = new OutputStub();

$file = new \Testing\Fixtures\Stubs\FileStub('App/Model/Service/FooBarService.php');

$file = FileStub::createWithoutConstructor()
	->setProperty('path', 'App/Model/Service/FooBarService.php')
	->setProperty('marksSorted', FALSE)
	->setProperty('marks', [
		MarkStub::createWithoutConstructor()
			->setProperty('offset', 1)
			->setProperty('line', 1)
			->setProperty('column', 1)
			->setProperty('type', MarkStub::TYPE_ERROR)
			->setProperty('text', 'Foo bar baz qux'),
		MarkStub::createWithoutConstructor()
			->setProperty('offset', 5293)
			->setProperty('line', 259)
			->setProperty('column', 22)
			->setProperty('type', MarkStub::TYPE_ERROR)
			->setProperty('text', 'Alpha beta gamma delta epsilon zeta'),
		MarkStub::createWithoutConstructor()
			->setProperty('offset', 728)
			->setProperty('line', 32)
			->setProperty('column', 10)
			->setProperty('type', MarkStub::TYPE_ERROR)
			->setProperty('text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer malesuada ante'
				. ' magna, ac suscipit velit viverra sed'),
	]);

$table = new Table($output, $file);

$table->render();

Assert::equal(<<<'END'
Details for App/Model/Service/FooBarService.php:
ERROR   1: 1 Foo bar baz qux
ERROR  32:10 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
             malesuada ante magna, ac suscipit velit viverra sed
ERROR 259:22 Alpha beta gamma delta epsilon zeta

END
, $output->getContent());

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$output = new OutputStub();

$file = FileStub::createWithoutConstructor()
	->setProperty('path', 'tests/App/Presenter/UserPresenter.phpt')
	->setProperty('marksSorted', FALSE)
	->setProperty('marks', [
		MarkStub::createWithoutConstructor()
			->setProperty('offset', 912)
			->setProperty('line', 42)
			->setProperty('column', 20)
			->setProperty('type', MarkStub::TYPE_WARNING)
			->setProperty('text', 'The answer to the ultimate question of life the universe and everything'),
		MarkStub::createWithoutConstructor()
			->setProperty('offset', 1129)
			->setProperty('line', 50)
			->setProperty('column', 66)
			->setProperty('type', MarkStub::TYPE_ERROR)
			->setProperty('text',
				'Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupokaiwhenuakitanatahu'
					. ' is the Maori name for a hill, 305 metres high, close to Porangahau, south of Waipukurau in'
					. ' southern Hawke\'s Bay, New Zealand.'),
	]);

$table = new Table($output, $file);

$table->render();

Assert::equal(<<<'END'
Details for tests/App/Presenter/UserPresenter.phpt:
WARNING 42:20 The answer to the ultimate question of life the universe and
              everything
ERROR   50:66 Taumatawhakatangihangakoauauotamateaturipukakapikimaungahoronukupo
              kaiwhenuakitanatahu is the Maori name for a hill, 305 metres high,
              close to Porangahau, south of Waipukurau in southern Hawke's Bay,
              New Zealand.

END
, $output->getContent());
