<?php

namespace MezzoLabs\Mezzo\Core\Schema\Converters\Eloquent;


use MezzoLabs\Mezzo\Core\Annotations\AnnotationReader;
use MezzoLabs\Mezzo\Core\Database\DatabaseColumn;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Core\Schema\Columns\JoinColumn;
use MezzoLabs\Mezzo\Core\Schema\Converters\Annotations\ModelAnnotationsConverter;
use MezzoLabs\Mezzo\Core\Schema\Converters\ModelConverter;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Exceptions\UnexpectedException;

class ModelReflectionConverter extends ModelConverter
{


    /**
     * @var DatabaseColumnConverter
     */
    protected $attributeConverter;

    /**
     * @var ModelAnnotationsConverter
     */
    protected $annotationsConverter;

    /**
     * Create a new ModelReflection Converter instance
     *
     * @param DatabaseColumnConverter $columnsConverter
     */
    public function __construct(DatabaseColumnConverter $columnsConverter, ModelAnnotationsConverter $annotationsConverter)
    {
        $this->attributeConverter = $columnsConverter;
        $this->annotationsConverter = $annotationsConverter;
    }

    /**
     * Perform a conversion from ModelReflection to ModelSchema
     *
     * @param $modelReflection
     * @return ModelSchema
     */
    public function run($modelReflection)
    {
        return $this->fromModelReflectionToSchema($modelReflection);
    }

    /**
     * @param ModelReflection $reflection
     * @return ModelSchema
     * @throws UnexpectedException
     */
    protected function fromModelReflectionToSchema(ModelReflection $reflection)
    {
        if ($reflection instanceof MezzoModelReflection)
            return $this->fromMezzoReflectionToSchema($reflection);

        if ($reflection instanceof EloquentModelReflection)
            return $this->fromEloquentReflectionToSchema($reflection);

        throw new UnexpectedException();
    }

    protected function fromMezzoReflectionToSchema(MezzoModelReflection $reflection)
    {
        return $this->annotationsConverter->run($reflection->annotations());
    }

    /**
     * Converts a EloquentModelReflection to a more generic ModelSchema
     *
     * @param EloquentModelReflection $reflection
     * @return ModelSchema
     */
    protected function fromEloquentReflectionToSchema(EloquentModelReflection $reflection)
    {
        $schema = new ModelSchema($reflection->className(), $reflection->databaseTable()->name(),
            $reflection->specialOptionProperties()
        );

        // Add all columns to the model schema that are atomic / in the main table,
        $reflection->databaseTable()->allColumns()->each(
            function (DatabaseColumn $column) use ($schema) {
                $attribute = $this->attributeConverter->viaDatabaseColumn($column);
                $schema->addAttribute($attribute);
            });

        // Add the join columns of other tables that are connected via relationships
        $eloquentModelReflector = mezzo()->makeReflectionManager()->eloquentModelsReflector();
        $joinColumns = $eloquentModelReflector->relationSchemas()->joinColumns();

        $joinColumns->each(
            function (JoinColumn $column) use ($schema) {

                if (!$column->relation()->connectsTable($schema->tableName()))
                    return;

                $attribute = $this->attributeConverter->viaJoinColumn($column);

                if (!$schema->hasAttribute($column->name())) {
                    $attribute->setPersisted($column->isPersisted());
                    $schema->addAttribute($attribute);
                }

            });

        return $schema;
    }
}