<?php


namespace MezzoLabs\Mezzo\Core\Schema\Converters\Annotations;


use MezzoLabs\Mezzo\Core\Annotations\Reader\AttributeAnnotations;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AtomicAttribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\Converters\Converter;
use MezzoLabs\Mezzo\Core\Schema\Relations\RelationSide;

class AttributeAnnotationsConverter extends Converter
{

    /**
     * @param $toConvert
     * @return Attribute
     */
    public function run($toConvert)
    {
        return $this->fromAttributeAnnotations($toConvert);
    }

    /**
     * @param AttributeAnnotations $annotations
     * @return Attribute
     */
    public function fromAttributeAnnotations(AttributeAnnotations $annotations)
    {
        if ($annotations->isRelation())
            return $this->makeRelationAttribute($annotations);

        return $this->makeAtomicAttribute($annotations);
    }

    /**
     * @param AttributeAnnotations $annotations
     * @return RelationAttribute
     */
    protected function makeRelationAttribute(AttributeAnnotations $annotations)
    {
        $relationSide = new RelationSide($annotations->relation(), $annotations->model()->tableName());
        return new RelationAttribute($annotations->name(), $relationSide, $annotations->options());
    }

    /**
     * @param AttributeAnnotations $annotations
     * @return AtomicAttribute
     */
    protected function makeAtomicAttribute(AttributeAnnotations $annotations)
    {
        return new AtomicAttribute($annotations->name(), $annotations->inputType(), $annotations->options());
    }
}