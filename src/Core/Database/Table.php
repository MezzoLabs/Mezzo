<?php


namespace MezzoLabs\Mezzo\Core\Database;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentRelationshipReflections;
use MezzoLabs\Mezzo\Core\Reflection\Reflectors\MezzoModelsReflector;

class Table
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Model
     */
    protected $instance;

    /**
     * Class name of the model
     *
     * @var string
     */
    private $model;

    /**
     * @var DatabaseColumns
     */
    protected $columns;

    /**
     * @var ModelReflection
     */
    protected $reflection;

    /**
     * @var Collection
     */
    protected $joiningColumns;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;

        $this->reflection = mezzo()->makeReflectionManager()->eloquentReflection($model);
        $this->instance = $this->reflection->instance();
        $this->columns = new DatabaseColumns($this);

    }

    /**
     * @return Collection
     */
    public function allColumns()
    {
        return $this->databaseColumns()->all();
    }

    /**
     * @return DatabaseColumns
     */
    public function databaseColumns()
    {
        if ($this->columns->all()->count() == 0) {
            $this->columns->readFromDatabase($this);
        }

        return $this->columns;
    }

    /**
     * @param DatabaseColumn $column
     * @return string
     */
    public function qualifiedColumnName(DatabaseColumn $column)
    {
        return $this->name() . '.' . $column->name();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->columns->name();
    }

    /**
     * @param ModelReflection $wrapper
     * @return Table
     */
    public static function fromModelReflection(ModelReflection $wrapper)
    {
        $instance = $wrapper->instance();
        $table = new Table(get_class($instance));
        return $table;
    }


    /**
     * @return Model
     */
    public function instance()
    {
        return $this->instance;
    }

    /**
     * @param Model $instance
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return EloquentRelationshipReflections
     */
    public function relationships()
    {
        return $this->reflection->relationshipReflections();
    }

    /**
     * @return Collection
     */
    public function joiningColumns()
    {
        if (!$this->joiningColumns)
            $this->joiningColumns = mezzo()->reflector()->relationSchemas()->joinColumns($this->name());

        return $this->joiningColumns;
    }

    /**
     * @return ModelReflection
     */
    public function modelReflection()
    {
        return $this->reflection;
    }


}