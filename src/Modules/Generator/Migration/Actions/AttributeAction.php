<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Migration\Actions;


use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Exceptions\MezzoException;
use MezzoLabs\Mezzo\Modules\Generator\NoTableFoundException;

abstract class AttributeAction extends Action
{

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * Creates a new generator action that is based on an attribute.
     *
     * @param Attribute $attribute
     * @throws MezzoException
     */
    public function __construct(Attribute $attribute)
    {

        if (!$attribute->hasTable()) {
            throw new NoTableFoundException($attribute);
        }

        $this->attribute = $attribute;
    }

    /**
     * @return Attribute
     */
    public function attribute()
    {
        return $this->attribute;
    }

    /**
     * Returns the name of the table which this action affects.
     *
     * @return string
     */
    public function tableName()
    {
        return $this->attribute()->getTable();
    }


    /**
     * @throws MezzoException
     * @return string
     */
    public function qualifiedName()
    {
        if ($this instanceof CreateAction)
            return "create." . $this->attribute()->qualifiedName();

        if ($this instanceof RemoveAction)
            return "remove." . $this->attribute()->qualifiedName();

        if ($this instanceof UpdateAction)
            return "update." . $this->attribute()->qualifiedName();

        throw new MezzoException('Cannot get qualified name.');
    }

}