<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Reader;

use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Exceptions\AnnotationException;
use MezzoLabs\Mezzo\Exceptions\ReflectionException;
use ReflectionProperty;


abstract class PropertyAnnotations
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Annotations
     */
    protected $annotations;
    /**
     * @var ModelAnnotations
     */
    protected $model;

    /**
     * Use the Factory make method
     *
     * @param string $name
     * @param Annotations $annotations
     * @param ModelAnnotations $model
     */
    final protected function __construct($name, Annotations $annotations, ModelAnnotations $model)
    {
        $this->name = $name;
        $this->annotations = $annotations;


        $this->validate();
        $this->model = $model;
    }

    /**
     * Checks if the given annotations list is correct.
     *
     * @return boolean
     */
    abstract protected function validate();

    /**
     * Read the annotations out of a property and creates the correct annotations object.
     *
     * @param AnnotationReader $reader
     * @param ReflectionProperty $property
     * @param ModelAnnotations $model
     * @return AttributeAnnotations|RelationAnnotations|null
     * @throws ReflectionException
     */
    public static function make(AnnotationReader $reader, ReflectionProperty $property, ModelAnnotations $model)
    {
        if (!$property->isProtected()) return null;

        $annotations = $reader->getPropertyAnnotations($property);


        if ($annotations->count() === 0) return null;

        // Remove one "_" from the property name. If we would use the correct attribute names
        // for the annotations, eloquent would freak out.
        $name = $property->getName();

        if(Str::startsWith($name, '_'))
            $name = substr($name, 1);

        return static::makeByAnnotationCollection($name, $annotations, $model);

    }

    /**
     * @param $name
     * @param Annotations $annotations
     * @param ModelAnnotations $model
     * @return AttributeAnnotations|RelationAnnotations
     * @throws ReflectionException
     */
    protected static function makeByAnnotationCollection($name, Annotations $annotations, ModelAnnotations $model)
    {
        $type = $annotations->type();

        if (!$type) return null;

        if ($type === RelationAnnotations::class)
            return new RelationAnnotations($name, $annotations, $model);

        if ($type == AttributeAnnotations::class)
            return new AttributeAnnotations($name, $annotations, $model);

        throw new ReflectionException('Unexpected annotation type :' . $type);
    }

    public function get($annotationType)
    {
        if (!$this->has($annotationType))
            throw new AnnotationException('Annotation ' . $annotationType . ' not found in ' . $this->name);

        return $this->annotations->get($annotationType);
    }

    /**
     * @param string $annotationType
     * @return bool
     */
    public function has($annotationType)
    {
        return $this->annotations->have($annotationType);
    }

    /**
     * @return string
     */
    public function qualifiedName()
    {
        return $this->model()->modelClassName() . '.' . $this->name();
    }

    /**
     * @return ModelAnnotations
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    public function qualifiedColumn(){
        return $this->model()->tableName() . '.' . $this->name();
    }

    /**
     * @return Annotations
     */
    public function annotations()
    {
        return $this->annotations;
    }

    /**
     * @return ModelReflection
     */
    public function modelReflection()
    {
        return $this->model->modelReflection();
    }


}