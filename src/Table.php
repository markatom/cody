<?php

namespace Markatom\Cody;

use Markatom\Cody\Utils\Position;
use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class Table extends Object
{

	const ERROR = 'ERROR';

	const LINE_LENGTH = 80;

	/** @var Output */
	private $output;

	/** @var SourceCode */
	private $source;

	public function __construct(Output $output, SourceCode $source)
    {
		$this->source = $source;
		$this->output = $output;
	}

	public function render()
	{
		$this->renderHeader();

		$columnWidths = $this->getColumnWidths();

		foreach ($this->source->getErrors() as $error) {
			$this->renderError($error, $columnWidths);
		}
	}

	private function renderHeader()
	{
		$this->output->writeLine('Details for ' . $this->source->getFile()->getPath() . ':');
	}

	private function getColumnWidths()
	{
		$widths = [0, 0, 0];

		foreach ($this->source->getErrors() as $error) {
			if (strlen(self::ERROR) > $widths[0]) {
				$widths[0] = strlen(self::ERROR);
			}

			if (strlen($error->line) > $widths[1]) {
				$widths[1] = strlen($error->line);
			}

			if (strlen($error->column) > $widths[2]) {
				$widths[2] = strlen($error->column);
			}
		}

		return $widths;
	}

	private function renderError(Error $mark, array $columnWidths)
	{
		$row = self::ERROR;
		$row .= str_repeat(' ', $columnWidths[0] - strlen(self::ERROR));

		$row .= ' ';

		$row .= str_repeat(' ', $columnWidths[1] - strlen($mark->line));
		$row .= $mark->line;

		$row .= ':';

		$row .= str_repeat(' ', $columnWidths[2] - strlen($mark->column));
		$row .= $mark->column;

		$row .= ' ';

		$this->output->write($row);

		$this->renderWordWrappedText($mark->text, strlen($row));

		$this->output->writeLine('');
	}

	private function renderWordWrappedText($text, $shift)
	{
		$words = explode(' ', $text);

		$position = $shift;
		while ($word = reset($words)) {
			if ($position === $shift) { // first word on line
				if ($position + strlen($word) > self::LINE_LENGTH) { // word does not fit
					$first  = substr($word, 0, self::LINE_LENGTH - $position); // cut it
					$second = substr($word, self::LINE_LENGTH - $position);

					$this->output->writeLine($first); // write first part and break line
					$this->output->write(str_repeat(' ', $shift)); // add padding

					array_shift($words); // remove original word from list
					array_unshift($words, $second); // add second part to list

					$position = $shift; // reset position

				} else {
					$position += strlen($word); // shift by word length
					$this->output->write($word); // write word

					array_shift($words); // remove word from list
				}

			} else { // second and next word on line
				if ($position + strlen($word) + 1 > self::LINE_LENGTH) { // word with preceding space does not fit
					$this->output->writeLine(''); // break line
					$this->output->write(str_repeat(' ', $shift)); // add padding

					$position = $shift; // reset position

				} else {
					$position += strlen($word) + 1; // shift by word length and preceding space
					$this->output->write(' ' . $word); // write space and word

					array_shift($words); // remove word from list
				}
			}
		}
	}

}
