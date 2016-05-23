<?php


namespace MezzoLabs\Mezzo\Core\Schema\Converters\Annotations;


use MezzoLabs\Mezzo\Core\Annotations\Reader\AttributeAnnotations;
use MezzoLabs\Mezzo\Core\Annotations\Reader\ModelAnnotations;
use MezzoLabs\Mezzo\Core\Schema\Converters\ModelConverter;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;

class ModelAnnotationsConverter extends ModelConverter
{

    /**
     * @var AttributeAnnotationsConverter
     */
    protected $attributeConverter;

    public function __construct(AttributeAnnotationsConverter $columnsConverter){

        $this->attributeConverter = $columnsConverter;
    }

    /**
     * @param $toConvert
     * @return ModelSchema
     */
    public function run($toConvert)
    {
        return $this->fromModelAnnotations($toConvert);
    }

    /**
     * @param ModelAnnotations $annotations
     * @return ModelSchema
     */
    public function fromModelAnnotations(ModelAnnotations $annotations)
    {
        $schema = new ModelSchema($annotations->modelClassName(), $annotations->tableName());

        $annotations->attributeAnnotatinos()->each(function(AttributeAnnotations $attributeAnnotations) use ($schema){
            $attribute = $this->attributeConverter->run($attributeAnnotations);
            $schema->addAttribute($attribute);
        });

        return $schema;

    }
}