<?php


namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;


use Illuminate\Database\Eloquent\Collection;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\InvalidModelException;
use MezzoLabs\Mezzo\Exceptions\ReflectionException;

class ModelReflectionSets extends Collection
{
    public $items;

    public $isOverallList = false;

    public function __construct($items = [])
    {
        parent::__construct($items);
    }


    /**
     * @param $model
     * @return ModelReflectionSet
     */
    public function getOrCreate($model)
    {
        if ($this->hasModel($model))
            return $this->getReflectionSet($model);
        else
            return $this->makeAndAddReflectionSet($model);
    }

    /**
     * Check if this reflection collection has the model.
     *
     * @param $model
     * @return bool
     */
    public function hasModel($model)
    {
        return $this->getReflectionSet($model) !== null;
    }

    /**
     * @param mixed $model
     * @return ModelReflectionSet|null
     */
    public function getReflectionSet($model)
    {
        if($model instanceof ModelReflection)
            $model = $model->className();

        if($model instanceof ModelReflectionSet)
            $model = $model->className();

        if($model instanceof ModelSchema)
            $model = $model->className();

        if(is_object($model))
            $model = get_class($model);

        if ($this->has($model))
            return $this->get($model);

        if ($this->has('App\\' . $model))
            return $this->get('App\\' . $model);

        $fromLookup = $this->getFromLookup($model);
        if ($fromLookup) return $fromLookup;

        return null;
    }

    /**
     * @param $model
     * @return ModelReflectionSet|null
     */
    private function getFromLookup($model)
    {
        return mezzo()->makeModelLookup()->find($model);
    }

    /**
     * @param ModelReflectionSet $reflectionSet
     * @return ModelReflectionSet
     * @throws ReflectionException
     */
    protected function makeAndAddReflectionSet($reflectionSet)
    {
        $reflectionSet = $this->makeReflectionSet($reflectionSet);
        $this->addReflectionSet($reflectionSet);
    }

    /**
     * Really create a new model reflection set.
     *
     * @param $className
     * @return ModelReflectionSet
     * @throws InvalidArgumentException
     */
    protected function makeReflectionSet($className)
    {
        if(!$className)
            throw new InvalidArgumentException($className);

        if ($className instanceof ModelReflectionSet)
            return $className;

        if (is_string($className))
            return new ModelReflectionSet($className);

        throw new InvalidModelException($className);

    }

    /**
     * @param ModelReflectionSet $reflectionSet
     */
    public function addReflectionSet(ModelReflectionSet $reflectionSet)
    {
        if ($this->has($reflectionSet->className()))
            return;

        $this->put($reflectionSet->className(), $reflectionSet);
        $this->addToMapping($reflectionSet);
        $this->addToOverallList($reflectionSet);
    }

    protected function addToMapping(ModelReflectionSet $reflectionSet)
    {
        mezzo()->makeModelLookup()->add($reflectionSet);

    }

    protected function addToOverallList(ModelReflectionSet $reflectionSet)
    {
        if ($this->isOverallList) return false;

        static::overall()->makeAndAddReflectionSet($reflectionSet);

    }

    /**
     * Return the global ModelReflectionSets collection
     *
     * @return ModelReflectionSets
     */
    public static function overall()
    {
        return mezzo()->makeReflectionManager()->sets();

    }

    public function offsetSet($key, $value)
    {
        if (!($value instanceof ModelReflectionSet))
            throw new InvalidArgumentException($value);

        parent::offsetSet($key, $value);
    }


    public function push($value)
    {
        if (!($value instanceof ModelReflectionSet))
            throw new InvalidArgumentException($value);

        parent::push($value);
    }

    /**
     * @return ModelReflections
     */
    public function mezzoReflections()
    {
        return ModelReflections::fromModelReflectionSets($this->mezzoModelSets());

    }

    /**
     * @return ModelReflectionSets
     * @throws \Exception
     */
    public function mezzoModelSets()
    {
        return $this->filter(function (ModelReflectionSet $reflectionSet) {
            return $reflectionSet->isMezzoModel();
        });

    }

    /**
     * @return ModelReflections
     */
    public function eloquentReflections()
    {
        return ModelReflections::fromModelReflectionSets($this->eloquentSets());

    }

    /**
     * @return ModelReflectionSets
     * @throws \Exception
     */
    public function eloquentSets()
    {
        return $this->filter(function (ModelReflectionSet $reflectionSet) {
            return !$reflectionSet->isMezzoModel();
        });
    }
}