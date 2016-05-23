<?php


namespace MezzoLabs\Mezzo\Core\Reflection\Reflectors;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Reflection\ModelFinder;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSets;
use MezzoLabs\Mezzo\Core\Schema\ModelSchemas;
use MezzoLabs\Mezzo\Core\Schema\RelationSchemas;

abstract class ModelsReflector
{
    /**
     * @var ModelFinder
     */
    protected $finder;

    /**
     * @var Mezzo
     */
    protected $mezzo;

    /**
     * @var RelationSchemas
     */
    protected $relationSchemas;

    /**
     * @var ModelSchemas
     */
    protected $modelSchemas;

    /**
     * @var Collection
     */
    protected $modelClasses;

    /**
     * @var ModelReflectionSets
     */
    protected $modelReflections;

    /**
     * Create a new model reflector instance.
     *
     * @param ModelFinder $finder
     * @param Mezzo $mezzo
     */
    final public function __construct(ModelFinder $finder, Mezzo $mezzo){
        $this->finder = $finder;
        $this->mezzo = $mezzo;
    }

    /**
     * Gets the filtered model reflection sets.
     *
     * @return ModelReflectionSets
     */
    abstract public function modelReflectionSets();

    /**
     * @return ModelReflectionSet
     */
    abstract public function modelReflectionSet($className);

    /**
     * Retrieve the correct model classes from the ModelFinder.
     *
     * @return Collection
     */
    abstract protected function findModelClasses();

    /**
     * Get all the model reflection instances for this reflector.
     *
     * @param bool|true $useMezzoReflections
     * @return Collection
     */
    public function findModelReflections($useMezzoReflections = true){
        $modelReflections = new Collection();

        $this->manager()->sets()->each(function (ModelReflectionSet $set, $key) use ($modelReflections, $useMezzoReflections) {
            $modelReflection = ($useMezzoReflections) ? $set->mezzoReflection() : $set->eloquentReflection();

            if (empty($modelReflection)) return true;

            $modelReflections->put($key, $modelReflection);
        });

        return $modelReflections;
    }

    /**
     * Returns the found model class names.
     *
     * @return Collection
     */
    final public function modelClasses(){
        if(!$this->modelClasses)
            $this->modelClasses = $this->findModelClasses();

        return $this->modelClasses;
    }

    /**
     * Produces the relation schemas out of the given model
     * information.
     *
     * @return RelationSchemas
     */
    abstract protected function makeRelationSchemas();

    /**
     * Returns the relation schemas that were produced out of the given
     * model information.
     *
     * @return RelationSchemas
     */
    final public function relationSchemas()
    {
        if(!$this->relationSchemas)
            $this->relationSchemas = $this->makeRelationSchemas();

        return $this->relationSchemas;
    }

    /**
     * Produces the model schemas out of the given model information or
     * the database columns.
     *
     * @return ModelSchemas
     */
    abstract protected function makeModelSchemas();

    /**
     * Returns the model schemas that were produced out of the given
     * model information / database columns.
     *
     * @return ModelSchemas
     */
    final public function modelSchemas()
    {
        if(!$this->relationSchemas)
            $this->relationSchemas = $this->makeRelationSchemas();

        return $this->relationSchemas;
    }

    /**
     * Make model reflection sets out of the  the model class names
     * and add them to a ModelReflectionSets collection.
     *
     * @param ModelReflectionSets $reflectionSets
     */
    public function addToSets(ModelReflectionSets $reflectionSets){
        foreach($this->modelClasses() as $modelClassName){
            $reflectionSets->getOrCreate($modelClassName);
        }
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\ReflectionManager
     */
    protected function manager(){
        return mezzo()->makeReflectionManager();
    }

}