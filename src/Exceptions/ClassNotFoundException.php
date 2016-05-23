<?php


namespace MezzoLabs\Mezzo\Exceptions;


class ClassNotFoundException extends MezzoException
{
    /**
     * You can only make a modelWrapper out of a class name (string) or out of an existing modelWrapper
     *
     * @param string $className
     */
    public function  __construct($className)
    {
        $this->message = "Class \"{$className}\" does not exist.";
    }
} 