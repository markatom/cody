<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Result extends Object
{

	const STATUS_OK            = 'ok';
	const STATUS_WARNINGS_ONLY = 'warningsOnly';
	const STATUS_VIOLATIONS    = 'violations';

	private $file;

	private $status = self::STATUS_OK;

	private $warnings = [];

	private $errors = [];

	/**
	 * @param string $file
	 */
	public function __construct($file)
	{
	    $this->file = $file;
	}

	public function addWarning(Token $token, $message)
	{
		if ($this->status === self::STATUS_OK) {
			$this->status = self::STATUS_WARNINGS_ONLY;
		}

		// todo
    }

	public function addViolation(Token $token, $message)
	{
		if ($this->status !== self::STATUS_VIOLATIONS) {
			$this->status = self::STATUS_VIOLATIONS;
		}

		// todo
	}

	public function getFile()
	{

	}

	/**
	 * @return array
	 */
	public function getWarnings()
	{

	}

	/**
	 * @return array
	 */
	public function getViolations()
	{

	}

	public function getStatus()
	{
		return $this->status;
	}

}