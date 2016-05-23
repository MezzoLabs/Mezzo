<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Exceptions;


use MezzoLabs\Mezzo\Exceptions\MezzoException;

class CannotFindCategoryGroupException extends MezzoException
{
    public function __construct($categoryGroup)
    {
        if(is_object($categoryGroup))
            $categoryGroup = get_class($categoryGroup);

        $this->add("Cannot find Category group \"{$categoryGroup}\".");
    }
}