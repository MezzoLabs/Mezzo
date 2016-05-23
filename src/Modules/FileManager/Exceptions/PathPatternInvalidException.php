<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Exceptions;


class PathPatternInvalidException extends FileManagerException
{
    public function __construct($message = "The pattern of this path is invalid.")
    {
       $this->message = $message;
    }
}