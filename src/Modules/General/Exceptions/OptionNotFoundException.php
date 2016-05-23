<?php


namespace MezzoLabs\Mezzo\Modules\General\Exceptions;


use MezzoLabs\Mezzo\Exceptions\MezzoException;

class OptionNotFoundException extends MezzoException
{
    public function __construct($searchOptionName)
    {
        $this->message = "There is no option with the name \"{$searchOptionName}\" found";
    }
}