<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @author Tomáš Markacz
 *
 * @property-read array $watchedTokens
 * @property-read array $options
 */
abstract class Watcher extends Object
{

	private $file;

	private $options;

	private $readOnly;

	private $result;

	/** @var Tokenizer */
	protected $tokenizer;

	public final function __construct(File $file, array $options, $readOnly)
	{
		$this->file     = $file;
		$this->options  = $options;
		$this->readOnly = $readOnly;

		$this->result = new Result($file);
	}

	/**
	 * @return array
	 */
	public abstract function watchedTokens();

	/**
	 * @return array
	 */
	public function definedOptions()
	{
		return [];
	}

	/**
	 * @param Token|JoinedTokens $token
	 * @param string $message
	 */
	public final function addError($token, $message)
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
