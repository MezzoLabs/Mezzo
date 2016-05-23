<?php

namespace MezzoLabs\Mezzo\Core\Schema\Columns;


abstract class Column
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function table()
    {
        return $this->table;
    }


    /**
     * @return string
     */
    public function qualifiedName()
    {
        return $this->table() . '.' . $this->name();
    }

} 