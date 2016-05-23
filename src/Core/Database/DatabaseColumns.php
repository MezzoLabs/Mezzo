<?php


namespace MezzoLabs\Mezzo\Core\Database;


use Illuminate\Support\Collection;

class DatabaseColumns
{
    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var string
     */
    private $tableName;

    /**
     * Initialize a theoretical playground for tables
     *
     * @param Table $table
     * @internal param $name
     */
    public function __construct(Table $table)
    {
        $this->tableName = $table->instance()->getTable();
        $this->table = $table;
        $this->columns = new Collection();
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->columns;
    }

    /**
     * Add a column to the collection.
     *
     * @param string $name
     * @param string $type
     */
    public function addColumn($name, $type)
    {
        $this->columns->put($name, $type);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function readFromDatabase()
    {
        $array = Reader::make()->getColumns($this->table);

        $this->columns = new Collection();
        foreach ($array as $doctrineColumn) {
            $this->addColumn($doctrineColumn->getName(), DatabaseColumn::fromDoctrine($doctrineColumn, $this->table));
        }

        return $this->columns;
    }

    /**
     * Return all the columns that only contain atomic data types.
     *
     * @return Collection
     */
    public function atomic()
    {
        return $this->all()->filter(function (DatabaseColumn $column) {
            return !$column->isForeignKey();
        });
    }

    /**
     * Return all the columns that are needed as a foreign key.
     *
     * @return Collection
     */
    public function foreignKeys()
    {
        return $this->all()->filter(function (DatabaseColumn $column) {
            return $column->isForeignKey();
        });
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->tableName;
    }

}