<?php

namespace Markatom\Cody;

use Nette\Object;

/**
 * @todo Fill desc.
 * @author Tomáš Markacz
 *
 * @property-read string $path
 * @property-read string $content
 * @property-read bool $modified
 */
class File extends Object
{

    /** @var string */
    private $path;

    /** @var string */
    private $content;

    /** @var bool */
    private $modified = FALSE;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path    = $path;
        $this->content = @file_get_contents($path); // intentionally @

		if ($this->content === FALSE) {
			throw self::readException($path);
		}
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $content
     */
    public function modify($content)
    {
        $this->modified = TRUE;
        $this->content  = $content;
    }

    /**
     * @return bool
     */
    public function isModified()
    {
        return $this->modified;
    }

    /**
     */
    public function save()
    {
        if (@file_put_contents($this->path, $this->content) === FALSE) { // intentionally @
            throw self::writeException($this->path);
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $path
     * @return ReadException
     */
    private static function readException($path)
    {
        return new ReadException("Unable to read file $path.");
    }

    /**
     * @param string $path
     * @return WriteException
     */
    private static function writeException($path)
    {
        return new WriteException("Unable to write file $path.");
    }

}
