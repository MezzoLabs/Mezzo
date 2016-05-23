<?php


namespace MezzoLabs\Mezzo\Core\Reflection;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;

class ModelLookup
{
    /**
     * @var Collection
     */
    protected $aliases;

    /**
     * @var Collection
     */
    protected $tableNames;


    /**
     * @param array $items
     * @internal param mixed $items
     */
    public function __construct($items = [])
    {
        $this->aliases = new Collection();
        $this->tableNames = new Collection();
    }

    /**
     * Add an alias so you can find the models via their short name.
     * (Tutorial or tutorials instead of \App\Learning\Tutorial)
     *
     * @param ModelReflectionSet $reflectionSet
     */
    public function add(ModelReflectionSet $reflectionSet)
    {
        $this->aliases->put(strtolower($reflectionSet->shortName()), $reflectionSet);

        $this->tableNames->put(strtolower($reflectionSet->instance()->getTable()), $reflectionSet);
    }

    /**
     * @param $model
     * @return ModelReflectionSet|null
     * @internal param $default
     */
    public function find($model)
    {
        if (!is_string($model))
            return null;

        $key = strtolower($model);

        if ($this->aliases->has($key))
            return $this->aliases->get($key);

        if ($this->tableNames->has($key)) {
            return $this->tableNames->get($key);
        }

        return null;
    }

}