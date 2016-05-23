<?php

namespace MezzoLabs\Mezzo\Core\Database;


class Key
{
    protected $tableName;
    protected $columnName;

    public function __construct($tableName, $columnName)
    {

        $this->tableName = $tableName;
        $this->columnName = $columnName;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return mixed
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param mixed $columnName
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;
    }
} 