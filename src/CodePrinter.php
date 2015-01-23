<?php

namespace Markatom\Cody;

use Markatom\Cody\Utils\Whitespace;
use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 */
class CodePrinter extends Object
{

    public function __construct(Output $output, $context)
    {
        $this->output  = $output;
        $this->context = $context;
    }

	public function write(Mark $mark)
    {
        $withoutTabs  = Whitespace::expandTabs($mark->file->content);
        $lines        = Whitespace::toLines($withoutTabs);
        $linesCount   = count($lines);
        $markedLines  = Whitespace::countLineBreaks($mark->marked) + 1;
        $contextBegin = $mark->line - $this->context;
        $contextEnd   = $mark->line + $markedLines + $this->context;

        if ($contextBegin < 0) {
            $contextBegin = 0;
        }

        if ($contextEnd >= $linesCount) {
            $contextEnd = $linesCount - 1;
        }

        $contextLines = array_slice($lines, $contextBegin, $contextEnd - $contextBegin, TRUE); // TRUE will preserve keys

        $shift = PHP_INT_MAX;
        foreach ($contextLines as $line) {
            $spaces = Whitespace::countLeadingSpaces($line);
            if ($spaces < $shift) {
                $shift = $spaces;
            }
        }

        foreach ($contextLines as $number => $line) {
            $shifted = substr($line, $shift);
            $this->output->writeLine($shifted);
        }
    }

}
