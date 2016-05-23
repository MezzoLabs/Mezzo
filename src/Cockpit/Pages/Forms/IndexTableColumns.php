<?php

namespace MezzoLabs\Mezzo\Cockpit\Pages\Forms;


use MezzoLabs\Mezzo\Core\Collection\StrictCollection;

class IndexTableColumns extends StrictCollection
{

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof IndexTableColumn;
    }


}