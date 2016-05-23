<?php

namespace MezzoLabs\Mezzo\Core\Reflection\Reflectors;

use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;
use MezzoLabs\Mezzo\Core\Schema\ModelSchemas;
use MezzoLabs\Mezzo\Core\Schema\RelationSchemas;

class MezzoModelsReflector extends ModelsReflector
{

    public function modelReflectionSets()
    {
        $allSets = $this->manager()->sets();

        return $allSets->filter(function (ModelReflectionSet $reflectionSet) {
            return $reflectionSet->isMezzoModel();
        });
    }

    /**
     * @return ModelReflectionSet
     */
    public function modelReflectionSet($className)
    {
        return $this->manager()->mezzoReflection($className)->modelReflectionSet();
    }


    /**
     * Retrieve the correct model classes from the ModelFinder.
     *
     * @return mixed
     */
    protected function findModelClasses()
    {
        return $this->finder->mezzoModelClasses();
    }

    /**
     * Produces the relation schemas out of the given model
     * information.
     *
     * @return RelationSchemas
     */
    protected function makeRelationSchemas()
    {
        $modelSchemas = $this->makeModelSchemas();

    }

    /**
     * Produces the model schemas out of the given model information or
     * the database columns.
     *
     * @return ModelSchemas
     */
    protected function makeModelSchemas()
    {
        $modelSchemas = new ModelSchemas();

        $this->modelReflections()->each(function(MezzoModelReflection $modelReflection) use ($modelSchemas){
            $modelSchemas->addSchema($modelReflection->schema());
        });


        return $modelSchemas;
    }

    /**
     * @return Collection
     */
    public function modelReflections()
    {
        return parent::findModelReflections(true);
    }


}