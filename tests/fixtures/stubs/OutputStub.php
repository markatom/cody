<?php

namespace Testing\Fixtures\Stubs;

use Markatom\Cody\Outputable;
use Nette\Object;
use Tester\Assert;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class OutputStub extends Object implements Outputable
{

	private $buffer = '';

	public function write($string)
	{
		$this->buffer .= $string;
	}

	public function writeLine($string)
	{
		$this->write($string . PHP_EOL);
	}

	public function assertContent($expected)
	{
		if ($this->buffer !== $expected) {
			Assert::fail('Unexpected output buffer %1, expected %2', $this->buffer, $expected);
		}
	}

	public function clearBuffer()
	{
		$this->buffer = '';
	}

}
