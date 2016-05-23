<?php

namespace MezzoLabs\Mezzo\Core\Schema\Columns;


use MezzoLabs\Mezzo\Core\Schema\Relations\Relation;

class JoinColumn extends Column
{
    /**
     * @var Relation
     */
    protected $relation;

    /**
     * @var bool
     */
    protected $persisted;


    /**
     * @param $name string The unqualified name of the column
     * @param $type string
     * @param $table string
     * @param Relation $relation
     */
    public function __construct($name, $type, $table, Relation $relation)
    {
        $this->name = $name;
        $this->relation = $relation;
        $this->type = $type;
        $this->table = $table;
    }


    /**
     * @return Relation
     */
    public function relation()
    {
        return $this->relation;
    }

    /**
     * @return boolean
     */
    public function isPersisted()
    {
        if ($this->persisted === null) $this->persisted = $this->checkPersisted();
        return $this->persisted;
    }

    /**
     * @return bool
     */
    protected function checkPersisted()
    {

        return mezzo()->makeDatabaseReader()->columnIsPersisted($this->name(), $this->table());
    }

} 