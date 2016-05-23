<?php

namespace MezzoLabs\Mezzo\Core\Schema\Columns;


class AtomicColumn extends Column
{

    /**
     * @param $name string The qualified name of the column
     * @param $type string
     * @param $table string
     */
    public function __construct($name, $type, $table)
    {

        $this->name = $name;
        $this->type = $type;
        $this->table = $table;
    }

} 