<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @author Tomáš Markacz
 */
abstract class Watcher extends Object
{

	private $file;

	private $options;

	private $readOnly;

	private $result;

	public final function __construct($file, array $options, $readOnly)
	{
		$this->file     = $file;
		$this->options  = $options;
		$this->readOnly = $readOnly;

		$this->result = new Result($file);
	}

	/**
	 * @return array
	 */
	public abstract function getWatchedTokens();

	public final function addWarning(Token $token, $message)
	{
		$this->output->addWarning($message);
	}

	public final function addError(Token $token, $message)
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
	 * @return bool
	 */
	public function isReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * @return Result
	 */
	public function getResult()
	{
		return $this->result;
	}

}
