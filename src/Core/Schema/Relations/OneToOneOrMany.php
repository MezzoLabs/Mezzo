<?php

namespace MezzoLabs\Mezzo\Core\Schema\Relations;


use MezzoLabs\Mezzo\Core\Schema\Columns\Columns;

abstract class OneToOneOrMany extends Relation
{
    /**
     * @var string
     */
    protected $joinTable;

    /**
     * @var string
     */
    protected $joinColumn;

    /**
     * Set up the connection from one table to another.
     *
     * @param string $columnName
     * @param bool|string $tableName
     * @return $this
     */
    public function connectVia($columnName, $tableName = false)
    {
        if (!$tableName) $tableName = $this->fromTable;

        $this->joinColumn = $columnName;
        $this->joinTable = $tableName;



        return $this;
    }

    public function qualifiedName()
    {
        return $this->joinTable . '.' . $this->joinColumn;
    }

    /**
     * @return string
     */
    public function joinTable()
    {
        return $this->joinTable;
    }

    /**
     * @return string
     */
    public function joinColumn()
    {
        return $this->joinColumn;
    }

    /**
     * @return Columns
     */
    protected function makeColumnsCollection()
    {
        $columns = new Columns();

        $columns->addAtomicColumn($this->fromPrimaryKey(), 'integer', $this->fromTable);
        $columns->addAtomicColumn($this->toPrimaryKey(), 'integer', $this->toTable);
        $columns->addJoinColumn($this->joinColumn, 'integer', $this->joinTable, $this);

        return $columns;
    }

    /**
     * @return array
     */
    protected function makeTablesArray()
    {
        return [
            $this->fromTable,
            $this->toTable
        ];
    }


}