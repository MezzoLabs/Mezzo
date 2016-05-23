<?php


namespace MezzoLabs\Mezzo\Exceptions;


class ModuleNotFound extends MezzoException
{
    public function __construct($module)
    {
        $this->message = 'Module cannot be found inside the ModuleCenter. ' .
            'Make sure that "' . $module . '" is a valid and registered Mezzo module.';
    }
}