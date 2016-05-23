<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Exceptions;


class FileNameNotUniqueException extends FileManagerException
{
    public function __construct($folder, $filename)
    {
        $this->add("The filename '{$filename}' is not unique in '{$folder}'.");
    }
}