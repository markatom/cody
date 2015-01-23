<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read Token[] $tokens
 * @property-read string $content
 */
class JoinedTokens extends Object
{

	/** @var Token[] */
	private $tokens;

	/**
	 * @param Token[] $tokens
	 */
	public function __construct(array $tokens)
	{
	    $this->tokens = $tokens;
	}

	/**
	 * @return Token[]
	 */
	public function getTokens()
	{
		return $this->tokens;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		$content = '';

		foreach ($this->tokens as $token) {
			$content .= $token->content;
		}

		return $content;
	}

}
