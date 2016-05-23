<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes;


use MezzoLabs\Mezzo\Core\Collection\StrictCollection;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentFieldTypeContract;

class ContentFieldTypeCollection extends StrictCollection
{

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof ContentFieldTypeContract;
    }
}