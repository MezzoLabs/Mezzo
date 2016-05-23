<?php

namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Annotations\Attribute;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Schema\Converters\Eloquent\ModelReflectionConverter;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Exceptions\InvalidModelException;

abstract class ModelReflection
{
    /**
     * @var ModelSchema
     */
    protected $schema;

    /**
     * @var ModelReflectionConverter
     */
    protected $schemaConverter;
    /**
     * @var array
     */
    protected $rules;
    /**
     * @var ModelReflectionSet
     */
    private $modelReflectionSet;

    /**
     * Constructor is private so the factory method has to be used.
     * This enables us to cache the reflections in an easy way.
     *
     * @param ModelReflectionSet $modelReflectionSet
     */
    public function __construct(ModelReflectionSet $modelReflectionSet)
    {
        $this->modelReflectionSet = $modelReflectionSet;

        $this->schemaConverter = ModelReflectionConverter::make();
    }

    /**
     * @param $model
     * @return mixed
     * @throws InvalidModelException
     */
    public static function modelStringOrFail($model)
    {
        $modelString = static::modelString($model);

        if (!$modelString)
            throw new InvalidModelException($model);

        return $modelString;
    }

    /**
     * Normalize the variable to a model string.
     *
     * @param $model
     * @return null|string
     */
    public static function modelString($model)
    {
        if (is_object($model) && $model instanceof ModelReflection)
            return $model->className();

        if (is_object($model))
            return get_class($model);

        if (class_exists($model))
            return $model;

        if (class_exists('App\\' . ucfirst($model)))
            return 'App\\' . ucfirst($model);

        return null;
    }


    /**
     * Full class name of the reflected eloquent model.
     *
     * @return string
     */
    public function className()
    {
        return $this->modelReflectionSet->className();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->modelReflectionSet->shortName();
    }

    /**
     * @param int $amount
     * @return string
     */
    public function title($amount = 1)
    {
        if (Lang::has('mezzo.models.' . strtolower($this->name())))
            return Lang::choice('mezzo.models.' . strtolower($this->name()), $amount);

        return $this->modelReflectionSet->shortName();
    }

    public function pluralTitle()
    {
        if (Lang::has('mezzo.models.' . strtolower($this->name())))
            return Lang::choice('mezzo.models.' . strtolower($this->name()), 2);

        return Str::plural($this->title());
    }

    public function slug()
    {
        return str_slug(space_case($this->name()));
    }

    /**
     * @param string $name
     * @return \MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes|Attribute
     */
    public function attributes(string $name = null)
    {
        if (!$name) {
            return $this->schema()->attributes()->orderByStringArray($this->instance(true)->getFillable());
        }

        return $this->schema()->attributes($name);
    }

    /**
     * @return ModelSchema
     */
    public function schema()
    {
        if (!$this->schema) {
            $this->schema = $this->schemaConverter->run($this);
        }

        return $this->schema;
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes
     */
    public function relations()
    {
        return $this->schema()->relations();
    }

    /**
     * @return boolean
     */
    public function isMezzoModel()
    {
        return $this instanceof MezzoModelReflection;
    }

    /**
     * @return array
     */
    public function rules($attribute = null)
    {
        if (!$this->rules) {
            $this->rules = $this->getRulesFromInstance();
        }

        if ($attribute) {
            return (isset($this->rules[$attribute])) ? $this->rules[$attribute] : "";
        }

        return $this->rules;
    }

    /**
     * Returns the columns that should be used for the fulltext search.
     *
     * @return array
     */
    public function searchable() : array
    {
        if (!property_exists($this->className(), 'searchable'))
            return [];

        return $this->instance()->searchable;
    }

    /**
     * Gets the rules from the Eloquent instance.
     *
     * @return array|mixed
     */
    protected function getRulesFromInstance()
    {
        if (method_exists($this->instance(), 'getRules'))
            return $this->instance()->getRules();

        if (method_exists($this->instance(), 'rules'))
            return $this->instance()->rules();

        $reflectionClass = Singleton::reflection($this->instance());

        if (!$reflectionClass->hasProperty('rules'))
            return [];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($this->instance());
    }

    /**
     * Returns an instance of the reflected Eloquent model.
     *
     * @param bool $forceNew
     * @return EloquentModel
     */
    public function instance($forceNew = false)
    {
        return $this->modelReflectionSet->instance($forceNew);
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*'])
    {
        return $this->instance()->all($columns);
    }

    /**
     * @return string
     */
    public function fileName()
    {
        return $this->reflectionClass()->getFileName();
    }

    /**
     * @return \ReflectionClass
     */
    public function reflectionClass()
    {
        return $this->modelReflectionSet()->reflectionClass();
    }

    /**
     * @return ModelReflectionSet
     */
    public function modelReflectionSet()
    {
        return $this->modelReflectionSet;
    }

    /**
     * Gives you an array of properties that influence eloquent behaviour
     */
    public function specialOptionProperties()
    {
        return [
            'hidden' => $this->instance()->getHidden(),
            'timestamps' => $this->instance()->usesTimestamps(),
            'fillable' => $this->instance()->getFillable(),
            'casts' => $this->getCastsArray()
        ];
    }

    /**
     * Read the protected casts array from a model.
     *
     * @return array
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     */
    public function getCastsArray()
    {
        $reflectionObject = Singleton::reflectionObject($this->instance());
        $property = $reflectionObject->getProperty('casts');
        $property->setAccessible(true);

        return $property->getValue($this->instance());
    }


}
