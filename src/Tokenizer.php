<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read File $file
 */
class Tokenizer extends Object
{

	/** @var File */
	private $file;

	/** @var Token[] */
	private $tokens = [];

	/** @var int */
	private $end;

	/** @var int */
	private $position;

	/**
	 * @param File $file
	 */
	public function __construct(File $file)
	{
		$this->file = $file;

		$this->tokenize();
	}

	/**
	 * @return File
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @return Token
	 */
	public function first()
	{
		return $this->tokens[$this->position = 0];
	}

	/**
	 * @return Token
	 */
	public function last()
	{
		return $this->tokens[$this->position = $this->end];
	}

	/**
	 * @return Token
	 */
	public function current()
	{
		return $this->tokens[$this->position];
	}

	/**
	 * @param array|int|string $search
	 * @param array|int|string $skip
	 * @return Token|NULL
	 */
	public function previous($search = NULL, $skip = NULL)
	{
		return $this->proceed($search, $skip, FALSE); // FALSE for backward
	}

	/**
	 * @param array|int|string $search
	 * @param array|int|string $skip
	 * @return Token|NULL
	 */
	public function next($search = NULL, $skip = NULL)
	{
		return $this->proceed($search, $skip, TRUE); // TRUE for forward
	}

	/**
	 * @param array|int|string $search
	 * @param array|int|string $skip
	 * @return JoinedTokens|NULL
	 */
	public function joinPreviousUntil($search = NULL, $skip = NULL)
	{
		return $this->proceed($search, $skip, FALSE, TRUE); // TRUE for backward, FALSE for join
	}

	/**
	 * @param array|int|string $search
	 * @param array|int|string $skip
	 * @return JoinedTokens|NULL
	 */
	public function joinNextUntil($search = NULL, $skip = NULL)
	{
		return $this->proceed($search, $skip, TRUE, TRUE); // TRUE twice for forward and join
	}

	/**
	 * @param string $content
	 */
	public function prepend($content)
	{
		$this->assertWritable();

		$offset = $this->tokens[$this->position]->offset;

		$this->file->modifyContent($offset, 0, $content);

		$this->tokenize();

		$shifted = $offset + strlen($content);

		for (; $this->tokens[$this->position]->offset < $shifted; $this->position++); // move to the original position
	}

	/**
	 * @param string $content
	 */
	public function append($content)
	{
		$this->assertWritable();

		$current = $this->tokens[$this->position];
		$offset  = $current->offset + $current->length;

		$this->file->modifyContent($offset, 0, $content);

		$this->tokenize();
	}

	/**
	 * @param string $content
	 */
	public function replace($content)
	{
		$this->assertWritable();

		$current = $this->tokens[$this->position];

		$this->file->modifyContent($current->offset, $current->length, $content);

		$this->tokenize();

		if (!isset($this->tokens[$this->position])) {
			$this->position = $this->end;
		}
	}

	/**
	 */
	public function remove()
	{
		$this->replace('');
	}

	/**
	 */
	private function tokenize()
	{
		$offset = 0;
		$tokens = token_get_all($this->file->content);

		$this->tokens = [];
		$this->end    = count($tokens);

		foreach ($tokens as $token) {
			list($type, $content) = is_string($token)
				? [$token, $token]
				: $token;

			$this->tokens[] = new Token($offset, $type, $content);
			$offset += strlen($content);
		}
	}

	/**
	 * @param array|int|string $search
	 * @param array|int|string $skip
	 * @param bool $forward
	 * @param bool $join
	 * @return JoinedTokens|Token|NULL
	 */
	private function proceed($search, $skip, $forward, $join = FALSE)
	{
		$delta   = $forward ? 1 : -1;
		$skipped = [];

		for ($i = $this->position + $delta; 0 <= $i && $i <= $this->end; $i += $delta) {
			if (!$search) {
				return $this->tokens[$this->position = $i];

			} elseif ($this->tokens[$i]->is($search)) {
				$this->position = $i;

				return $join
					? new JoinedTokens($skipped)
					: $this->tokens[$i];

			} elseif ($skip && !$this->tokens[$i]->is($skip)) {
				break;

			}

			$skipped[] = $this->tokens[$i];
		}

		return NULL;
	}

	/**
	 * @throws ReadOnlyException
	 */
	private function assertWritable()
	{
		if ($this->file->readOnly) {
			throw new ReadOnlyException;
		}
	}

}
