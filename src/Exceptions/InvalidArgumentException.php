<?php


namespace MezzoLabs\Mezzo\Exceptions;


class InvalidArgumentException extends MezzoException
{

    /**
     * You can only make a modelWrapper out of a class name (string) or out of an existing modelWrapper
     *
     * @param string|object $invalidArgument
     * @param string $functionName
     */
    public function  __construct($invalidArgument, $functionName = "")
    {
        $type = gettype($invalidArgument);

        if ($type === 'object')
            $type = get_class($invalidArgument);

        if(empty($functionName))
            $functionName = $this->getCallingFunction();

        $this->message = $type . ' is an invalid argument for ' . $functionName . '.';
    }
} 