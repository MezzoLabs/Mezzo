<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes;


use MezzoLabs\Mezzo\Core\Collection\StrictCollection;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentBlockTypeContract;

class ContentBlockTypeCollection extends StrictCollection
{

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof ContentBlockTypeContract;
    }
}