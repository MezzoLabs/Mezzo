<?php


namespace MezzoLabs\Mezzo\Exceptions;


class UnexpectedException extends MezzoException
{
    public function __construct($msg = "")
    {
        if (!empty($msg)) $this->add($msg);

        $this->add('Please report this exception to the core team.');
    }
}