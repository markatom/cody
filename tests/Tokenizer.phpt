<?php

use Markatom\Cody\SourceCode;
use Markatom\Cody\ReadOnlyException;
use Markatom\Cody\Tokenizer;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';


$tokenizer = new Tokenizer(new SourceCode(__DIR__ . '/files/Tokenizer/in.php', TRUE)); // read only

Assert::equal(70, $tokenizer->moveNext(T_CLASS)->getNext('{')->offset);

Assert::equal(T_ABSTRACT, $tokenizer->movePrev()->movePrev()->getCurrent()->type);

Assert::equal(50, $tokenizer->getPrev(T_WHITESPACE)->offset);

Assert::equal("\n", $tokenizer->movePrev(T_WHITESPACE)->getCurrent()->content);

Assert::exception(function () use ($tokenizer) {
	$tokenizer->removeCurrent();
}, ReadOnlyException::class);

$tokenizer = new Tokenizer(new SourceCode(__DIR__ . '/files/Tokenizer/in.php', FALSE)); // writable

do {
	$tokenizer->moveNext(T_FUNCTION);
} while ($tokenizer->getNext(T_STRING)->content !== 'doQux');

$source = $tokenizer->movePrev(T_DOC_COMMENT)
	->replaceCurrent("/**\n\t * Does Hello world!\n\t */")
	->moveNext(T_FUNCTION)
	->insertBeforeCurrent('static ')
	->moveNext(T_ECHO)
	->moveNext(T_CONSTANT_ENCAPSED_STRING)
	->replaceCurrent("'Hello world!'")
	->moveNext()
	->insertAfterCurrent("\n\n\t\treturn TRUE;")
	->getFile()
	->getContent();

Assert::equal(file_get_contents(__DIR__ . '/files/Tokenizer/out.php'), $source);
