<?php


namespace MezzoLabs\Mezzo\Modules\General\Options;


use MezzoLabs\Mezzo\Core\Collection\StrictCollection;

class OptionFieldCollection extends StrictCollection
{

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof OptionField;
    }
}