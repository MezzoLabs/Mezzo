<?php


namespace MezzoLabs\Mezzo\Exceptions;


class DirectoryNotFound extends MezzoException
{
    public function __construct($directory)
    {
        $this->message = 'Cannot find directory ' . $directory . '.';
    }
}