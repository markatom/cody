<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Details extends Object
{

	private $totalCount;

	private $runTime;

	private $counter = 0;

	private $warningsCount = 0;

	private $errorsCount = 0;

	/** @var File[] */
	private $files = [];

	public function writeMessages()
	{
		foreach ($this->files as $file) {
			echo $file->getPath() . PHP_EOL;

			foreach ($file->marks as $marker) {
				list($line, $column) = Position::offsetToLineAndColumn($marker->offset, $file);

				echo "\t["
				. $marker->type === Mark::TYPE_ERROR ? '[ERROR at]' : '[WARNING at] '
					. $line // todo
					. ':'
					. $column
					. "]\t"
					. $marker->message
					. PHP_EOL;
			}
		}


	}

	public function writeSummary()
	{
		$formatted = number_format($this->runTime, 3);

		echo "Checked $this->totalCount files in $formatted seconds." . PHP_EOL;
		echo "Found $this->warningsCount warnings and $this->errorsCount violations against coding standards." . PHP_EOL . PHP_EOL;
	}

	public function writeHints()
	{
		echo "Run fix command to fix warnings and violations automatically." . PHP_EOL;
		echo "Run with show option to show warnings and violations in source code." . PHP_EOL;
	}

	private function prepareMessages(File $file)
	{
		$messages = [];

		foreach ($file->errorsCount as $error) {
			$position = Position::offsetToLineAndColumn($error->offset, $file);
			$messages[] = [
				'offset'  => $error->offset,
				'line'    => $position[0],
				'column'  => $position[1],
				'type'    => 'error',
				'message' => $error->message,
			];
		}

		foreach ($file->warnings as $warning) {
			$position = Position::offsetToLineAndColumn($warning->offset, $file);
			$messages[] = [
				'offset'  => $warning->offset,
				'line'    => $position[0],
				'column'  => $position[1],
				'type'    => 'warning',
				'message' => $warning->message,
			];
		}

		usort($markers, function (array $a, array $b) {
			return $a['offset'] - $b['offset'];
		});

		return $markers;
	}

}