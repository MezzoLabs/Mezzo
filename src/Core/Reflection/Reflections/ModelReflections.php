<?php


namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;


use Illuminate\Database\Eloquent\Collection;

class ModelReflections extends Collection
{
    /**
     * @param ModelReflectionSets $modelReflectionSets
     * @param bool|false $forceEloquentReflections
     * @return ModelReflections
     */
    public static function fromModelReflectionSets(ModelReflectionSets $modelReflectionSets, $forceEloquentReflections = false)
    {
        $modelReflections = new ModelReflections();

        $modelReflectionSets->each(
            function (ModelReflectionSet $modelReflectionSet) use ($forceEloquentReflections, $modelReflections) {
                $modelReflections->addReflection($modelReflectionSet->bestReflection($forceEloquentReflections));
            });

        return $modelReflections;
    }

    /**
     * @param ModelReflection $modelReflection
     */
    public function addReflection(ModelReflection $modelReflection)
    {
        $this->put($modelReflection->className(), $modelReflection);
    }
}