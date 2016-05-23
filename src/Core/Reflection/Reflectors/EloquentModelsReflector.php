<?php

namespace MezzoLabs\Mezzo\Core\Reflection\Reflectors;

use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentRelationshipReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSets;
use MezzoLabs\Mezzo\Core\Schema\ModelSchemas;
use MezzoLabs\Mezzo\Core\Schema\RelationSchemas;

class EloquentModelsReflector extends ModelsReflector
{
    /**
     * Boot up the Reflector. Gather all the needed data.
     *
     * @return void
     */
    public function boot()
    {
        $this->modelReflections();
        $this->relationReflections();
        $this->relationSchemas();

    }

    /**
     * @return ModelReflectionSet
     */
    public function modelReflectionSet($className)
    {
        return $this->manager()->modelReflection($className);
    }

    /**
     * Get all relationReflections
     *
     * @return Collection
     */
    public function relationReflections()
    {
        return Singleton::get('relationReflections', function () {
            $allRelations = new Collection();

            /** @var EloquentModelReflection $reflectionSet */
            foreach ($this->modelReflections() as $eloquentReflection) {
                /** @var EloquentRelationshipReflection $relationshipReflection */
                foreach ($eloquentReflection->relationshipReflections() as $relationshipReflection) {
                    $allRelations->put($relationshipReflection->qualifiedName(), $relationshipReflection);
                }
            }

            return $allRelations;
        });
    }

    /**
     * Retrieve the correct model classes from the ModelFinder.
     *
     * @return mixed
     */
    protected function findModelClasses()
    {
        return $this->finder->eloquentModelClasses();
    }

    /**
     * Produces the relation schemas out of the given model
     * information.
     *
     * @return RelationSchemas
     */
    protected function makeRelationSchemas()
    {
        $relationReflections = $this->relationReflections();

        $relationSchemas = new RelationSchemas();

        $relationReflections->each(
            function (EloquentRelationshipReflection $reflection) use ($relationSchemas) {
                $relationSchemas->addRelation($reflection->relationSchema());
            });

        return $relationSchemas;
    }

    /**
     * Produces the model schemas out of the given model information or
     * the database columns.
     *
     * @return ModelSchemas
     */
    protected function makeModelSchemas()
    {
        $modelsSchema = new ModelSchemas();

        $this->modelReflections()->each(function (EloquentModelReflection $model) use ($modelsSchema) {
            $modelsSchema->addSchema($model->schema());
        });

        return $modelsSchema;
    }


    /**
     * Gets the filtered model reflection sets.
     *
     * @return ModelReflectionSets
     */
    public function modelReflectionSets()
    {
        $allSets = $this->manager()->sets();

        return $allSets->filter(function (ModelReflectionSet $reflectionSet) {
            return !$reflectionSet->isMezzoModel();
        });
    }

    /**
     * @return Collection
     */
    public function modelReflections()
    {
        return parent::findModelReflections(false);
    }


}