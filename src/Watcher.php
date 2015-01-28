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

	private $source;

	private $readOnly;

	protected $options;

	/** @var Tokenizer */
	protected $tokenizer;

	public final function __construct(SourceCode $source, array $options, $readOnly)
	{
		$this->source    = $source;
		$this->options   = $options;
		$this->readOnly  = $readOnly;
	}

	public function setTokenizer(Tokenizer $tokenizer)
	{
		$this->tokenizer = $tokenizer;
	}

	/**
	 * @return array
	 */
	public abstract function getWatchedTokens();

	/**
	 * @return array
	 */
	public static function getDefinedOptions()
	{
		return [];
	}

	/**
	 * @param Token|JoinedTokens $token
	 * @param string $message
	 */
	public final function addError($token, $message)
	{
		if (!$token instanceof Token && !$token instanceof JoinedTokens) {
			throw self::invalidTokenException($token);
		}

		$this->source->addError($token->getOffset(), $token->getLength(), $message);
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
	public final function isReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * @param mixed $given
	 * @return InvalidArgumentException
	 */
	private static function invalidTokenException($given)
	{
		$given = is_object($given)
			? get_class($given)
			: gettype($given);

		return new InvalidArgumentException("Token must be instance of Markatom\\Cody\\Token or Markatom\\Cody\\JoinedTokens, $given given.");
	}

}
