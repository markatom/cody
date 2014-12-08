<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
abstract class Preprocessor extends Object
{

	private $output;

	private $source;

	private $readOnly;

	public final function __construct(Output $output, $source, $readOnly)
	{
		$this->output   = $output;
		$this->source   = $source;
		$this->readOnly = $readOnly;
	}

	public function getSource()
	{
		return $this->source;
	}

	/**
	 * @param  int $offset
	 * @param  int $length
	 * @param  string $message
	 * @return self
	 */
	public final function addWarning($offset, $length, $message)
	{

	}

	/**
	 * XXX
	 *
	 * @param  int $offset XXX
	 * @param  int $length XXX
	 * @param  string $message XXX
	 * @return self XXX
	 * @access public
	 */
	public final function addError($offset, $length, $message)
	{
		trigger_error('Not Implemented!', E_USER_WARNING);
	}

	/**
	 * XXX
	 *
	 * @param  callable $function XXX
	 * @access public
	 */
	public final function fix(callable $function)
	{
		if (!$this->readOnly) {
			$function();
		}
	}

	/**
	 */
	public abstract function process();

}
