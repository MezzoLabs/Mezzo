<?php


namespace MezzoLabs\Mezzo\Core\Schema\Attributes;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\TextInput;
use MezzoLabs\Mezzo\Core\Schema\Relations\ManyToMany;

class PivotAttribute extends Attribute
{
    /**
     * @var ManyToMany
     */
    protected $relation;

    /**
     * @param $name
     * @param ManyToMany $relation
     * @param array $options
     */
    public function __construct($name, ManyToMany $relation, $options = [])
    {
        $this->name = $name;

        $this->setOptions($options);
        $this->type = $this->findType();
        $this->relation = $relation;
    }

    /**
     * Find out the input type based on the side of the relation we are on.
     *
     * @return RelationInputMultiple|RelationInputSingle
     */
    protected function findType()
    {
        $type = parent::findType();

        if ($type) {
            return $type;
        }

        return TextInput::class;
    }

    /**
     * @return ManyToMany
     */
    public function relation()
    {
        return $this->relation;
    }
}