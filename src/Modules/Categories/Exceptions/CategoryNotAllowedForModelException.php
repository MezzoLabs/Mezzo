<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Exceptions;


use MezzoLabs\Mezzo\Exceptions\MezzoException;

class CategoryNotAllowedForModelException extends MezzoException
{
    public function __construct($categoryGroup, $modelName)
    {
        if(is_object($categoryGroup))
            $categoryGroup = get_class($categoryGroup);

        $this->add("Cannot add the category '{$categoryGroup}'. " .
            "You have to add the entity '{$modelName}' to the category group.");
    }
}