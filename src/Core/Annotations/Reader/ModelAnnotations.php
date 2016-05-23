<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Reader;


use Doctrine\Common\Annotations\Reader as DoctrineReader;
use Illuminate\Database\Eloquent\Collection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputMultiple;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Exceptions\AnnotationException;

class ModelAnnotations
{
    /**
     * @var ModelReflection
     */
    protected $modelReflection;

    /**
     * @var array
     */
    protected $classAnnotations;

    /**
     * @var Collection
     */
    protected $attributeAnnotationsCollection;

    /**
     * @var Collection
     */
    protected $relationAnnotationsCollection;

    /**
     * @param ModelReflection $modelReflection
     */
    public function __construct(ModelReflection $modelReflection)
    {
        $this->modelReflection = $modelReflection;

        $this->read();

        $this->sendToCache();
    }

    protected function read()
    {
        $this->classAnnotations = $this->readClass();

        $this->readProperties();
    }

    /**
     * @return null|object
     */
    protected function readClass()
    {
        $reflectionClass = $this->reflectionClass();
        $classAnnotations = $this->doctrineReader()->getClassAnnotations($reflectionClass);

        return $classAnnotations;
    }

    /**
     * @return \ReflectionClass
     */
    public function reflectionClass()
    {
        return $this->modelReflection()->reflectionClass();
    }

    /**
     * @return ModelReflection
     */
    public function modelReflection()
    {
        return $this->modelReflection;

    }

    /**
     * @return DoctrineReader
     */
    protected function doctrineReader()
    {
        return $this->reader()->doctrineReader();

    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Annotations\Reader\AnnotationReader
     */
    protected function reader()
    {
        return mezzo()->makeAnnotationReader();
    }

    /**
     * Read all the properties in the given model anc check for attribute and realtionship reflection.
     */
    protected function readProperties()
    {
        $this->attributeAnnotationsCollection = new Collection();
        $this->relationAnnotationsCollection = new Collection();

        $reflectionClass = $this->reflectionClass();
        $properties = new Collection($reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED));

        $properties->each(function (\ReflectionProperty $property) {
            $this->readProperty($property);
        });
    }

    /**
     * @param \ReflectionProperty $property
     * @return bool
     * @throws AnnotationException
     */
    protected function readProperty(\ReflectionProperty $property)
    {
        $annotations = PropertyAnnotations::make($this->reader(), $property, $this);
        if ($annotations === null)
            return null;

        if ($annotations instanceof RelationAnnotations)
            return $this->addRelationAnnotations($annotations);

        if ($annotations instanceof AttributeAnnotations) {
            return $this->addAttributeAnnotations($annotations);
        }

        throw new AnnotationException('Unknown property ' . $annotations->name() .
            ' class ' . get_class($annotations));
    }

    protected function addRelationAnnotations(RelationAnnotations $annotations)
    {
        return $this->relationAnnotationsCollection->put($annotations->name(), $annotations);
    }

    protected function addAttributeAnnotations(AttributeAnnotations $annotations)
    {
        if ($annotations->inputType() instanceof RelationInputMultiple) {
            $this->addRelationAnnotations($annotations->toRelationAnnotations());
        }

        return $this->attributeAnnotationsCollection->put($annotations->name(), $annotations);
    }

    /**
     * Save this model annotations to the runtime cache.
     */
    protected function sendToCache()
    {
        $this->reader()->cache($this);
    }

    /**
     * @return string
     */
    public function modelClassName()
    {
        return $this->modelReflection->className();
    }

    /**
     * @return Collection
     */
    public function allAnnotations()
    {
        return $this->attributeAnnotationsCollection->merge($this->relationAnnotationsCollection);
    }

    /**
     * @return string
     */
    public function parentClassName()
    {
        return get_parent_class($this->modelReflection->className());
    }

    /**
     * @return ModelSchema
     */
    public function schema()
    {
        return $this->modelReflection()->schema();
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return $this->modelReflection()->modelReflectionSet()->tableName();
    }

    /**
     * @return Collection
     */
    public function attributeAnnotatinos()
    {
        return $this->attributeAnnotationsCollection;
    }

    /**
     * @return Collection
     */
    public function relationAnnotations()
    {
        return $this->relationAnnotationsCollection;
    }


}