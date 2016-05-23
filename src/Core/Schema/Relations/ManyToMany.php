<?php


namespace MezzoLabs\Mezzo\Core\Schema\Relations;


use MezzoLabs\Mezzo\Core\Database\DatabaseColumn;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes;
use MezzoLabs\Mezzo\Core\Schema\Columns\Columns;

class ManyToMany extends Relation
{

    /**
     * @var string
     */
    protected $pivotTable;

    /**
     * @var string
     */
    protected $pivotColumnFrom;

    /**
     * @var string
     */
    protected $pivotColumnTo;

    /**
     * @var Attributes
     */
    protected $pivotAttributes;

    /**
     * @param string $tableName
     * @param string $columnFrom
     * @param string $columnTo
     * @param array $columns
     * @return $this
     */
    public function setPivot($tableName, $columnFrom, $columnTo, Attributes $pivotAttributes = null)
    {
        $this->pivotTable = $tableName;

        $this->pivotColumnFrom = DatabaseColumn::disqualifyName($columnFrom);
        $this->pivotColumnTo = DatabaseColumn::disqualifyName($columnTo);

        $this->pivotAttributes = ($pivotAttributes) ? $pivotAttributes : new Attributes();

        return $this;
    }

    public function qualifiedName()
    {
        return $this->pivotTable;
    }

    /**
     * @return ManyToMany
     */
    static function make()
    {
        return parent::makeByType(static::class);
    }

    /**
     * @return mixed
     */
    public function pivotTable()
    {
        return $this->pivotTable;
    }

    /**
     * @return string
     */
    public function pivotColumnFrom()
    {
        return $this->pivotColumnFrom;
    }

    /**
     * @return string
     */
    public function pivotColumnTo()
    {
        return $this->pivotColumnTo;
    }

    /**
     * @return Attributes
     */
    public function pivotAttributes()
    {
        return $this->pivotAttributes;
    }

    protected function makeColumnsCollection()
    {
        $columns = new Columns();

        $columns->addAtomicColumn($this->fromPrimaryKey(), 'integer', $this->fromTable);
        $columns->addAtomicColumn($this->toPrimaryKey(), 'integer', $this->toTable);
        $columns->addJoinColumn($this->pivotColumnFrom, 'integer', $this->pivotTable, $this);
        $columns->addJoinColumn($this->pivotColumnTo, 'integer', $this->pivotTable, $this);

        return $columns;
    }

    /**
     * @return array
     */
    protected function makeTablesArray()
    {
        return [
            $this->fromTable,
            $this->toTable,
            $this->pivotTable
        ];
    }
}