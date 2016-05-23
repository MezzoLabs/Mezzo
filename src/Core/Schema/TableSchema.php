<?php

namespace MezzoLabs\Mezzo\Core\Schema;


use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes;

class TableSchema
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Attributes
     */
    protected $attributes;

    /**
     * @var ModelSchema
     */
    protected $model;


    /**
     * Creates a new table schema.
     * The model attribute is optional, since pivot tables are not related to one model
     *
     * @param $name
     * @param null $model
     */
    public function __construct($name, $model = null)
    {
        $this->attributes = new Attributes();
        $this->name = $name;
    }

    /**
     * @return Attributes
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @param Attribute $attribute
     * @return \MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes
     */
    public function addAttribute(Attribute $attribute)
    {
        return $this->attributes->addAttribute($attribute);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return ModelSchema
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param ModelSchema $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
} 