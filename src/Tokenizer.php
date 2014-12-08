<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Tokenizer extends Object
{

	/**
	 * @var string
	 */
	private $source;

	/**
	 * @var Token[]
	 */
	private $tokens = [];

	/**
	 * @var bool
	 */
	public $readOnly;

	/**
	 * @var int
	 */
	private $current = 0;

	/**
	 * @param  string $source
	 * @param  bool $readOnly
	 */
	public function __construct($source, $readOnly)
	{
		$this->source   = $source;
		$this->readOnly = $readOnly;

		$this->tokenize();
	}

	public function reset()
	{
		$this->current = 0;
	}

	/**
	 * @return string
	 */
	public function getSource()
	{
		return $this->source;
	}

	/**
	 * @return Token|NULL
	 */
	public function getCurrent()
	{
		return isset($this->tokens[$this->current])
			? $this->tokens[$this->current]
			: NULL;
	}

	/**
	 * @param  int $searchToken
	 * @return Token|NULL
	 */
	public function getPrev($searchToken = NULL)
	{
		if ($searchToken) {
			for ($i = $this->current - 1; $i >= 0; $i--) {
				if ($this->tokens[$i]->type === $searchToken) {
					return $this->tokens[$i];
				}
			}

			return NULL;

		} else {
			if ($this->current === 0) {
				return NULL;
			}

			return $this->tokens[$this->current - 1];
		}
	}

	/**
	 * @param  int $searchToken
	 * @return Token|NULL
	 */
	public function getNext($searchToken = NULL)
	{
		if ($searchToken) {
			for ($i = $this->current + 1, $count = count($this->tokens); $i < $count; $i++) {
				if ($this->tokens[$i]->type === $searchToken) {
					return $this->tokens[$i];
				}
			}

			return NULL;

		} else {
			if ($this->current === count($this->tokens) - 1) {
				return NULL;
			}

			return $this->tokens[$this->current + 1];
		}
	}

	/**
	 * @param  int $searchToken
	 * @return self
	 * @throws InvalidMoveException
	 */
	public function movePrev($searchToken = NULL)
	{
		if ($searchToken) {
			for ($this->current--; $this->current >= 0; $this->current--) {
				if ($this->tokens[$this->current]->type === $searchToken) {
					return $this;
				}
			}

			throw new InvalidMoveException('Cannot move to previous token ' . $this->getTokenName($searchToken) . '.');

		} else {
			if ($this->current === 0) {
				throw new InvalidMoveException('Cannot move to previous token.');
			}

			$this->current--;

			return $this;
		}
	}

	/**
	 * @param int $searchToken
	 * @return self
	 * @throws InvalidMoveException
	 */
	public function moveNext($searchToken = NULL)
	{
		if ($searchToken) {
			for ($this->current++, $count = count($this->tokens); $this->current < $count; $this->current++) {
				if ($this->tokens[$this->current]->type === $searchToken) {
					return $this;
				}
			}

			throw new InvalidMoveException('Cannot move to next token ' . $this->getTokenName($searchToken) . '.');

		} else {
			if ($this->current === count($this->tokens) - 1) {
				throw new InvalidMoveException('Cannot move to next token.');
			}

			$this->current++;

			return $this;
		}
	}

	/**
	 * @param $content
	 * @return self
	 */
	public function insertBeforeCurrent($content)
	{
		$this->assumeWritable();

		$cut    = $this->tokens[$this->current]->offset;
		$offset = $cut + strlen($content);

		$this->source = substr($this->source, 0, $cut) . $content . substr($this->source, $cut);

		$this->tokenize();

		for (; $this->tokens[$this->current]->offset !== $offset; $this->current++);

		return $this;
	}

	/**
	 * @param string $content
	 * @return self
	 */
	public function insertAfterCurrent($content)
	{
		$this->assumeWritable();

		$current = $this->tokens[$this->current];
		$cut     = $current->offset + strlen($current->content);

		$this->source = substr($this->source, 0, $cut) . $content . substr($this->source, $cut);

		$this->tokenize();

		return $this;
	}

	/**
	 * @param  string $content XXX
	 * @return self
	 */
	public function replaceCurrent($content)
	{
		$this->assumeWritable();

		$current = $this->tokens[$this->current];

		$this->source = substr($this->source, 0, $current->offset) . $content . substr($this->source, $current->offset + strlen($current->content));

		$this->tokenize();

		if (!isset($this->tokens[$this->current])) {
			$this->current = count($this->tokens) - 1;
		}

		return $this;
	}

	/**
	 * @return self
	 */
	public function removeCurrent()
	{
		$this->replaceCurrent('');

		return $this;
	}

	/**
	 */
	private function tokenize()
	{
		$offset = 0;
		$this->tokens = [];

		foreach (token_get_all($this->source) as $token) {
			list($type, $content) = count($token) === 1
				? [$token, $token]
				: $token;

			$this->tokens[] = new Token($offset, $type, $content);
			$offset += strlen($content);
		}
	}

	/**
	 * @throws ReadOnlyException
	 */
	private function assumeWritable()
	{
		if ($this->readOnly) {
			throw new ReadOnlyException(get_called_class());
		}
	}

	/**
	 * @param int|string $tokenType
	 * @return string
	 */
	private function getTokenName($tokenType)
	{
		return is_int($tokenType)
			? token_name($tokenType)
			: $tokenType;
	}

}
