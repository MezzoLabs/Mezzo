<?php


namespace MezzoLabs\Mezzo\Core\Schema\Attributes;


use ArrayAccess;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;

class AtomicAttribute extends Attribute
{
    /**
     * @param $name
     * @param \MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType $inputType
     * @param array|ArrayAccess $options
     */
    public function __construct($name, InputType $inputType, $options = [])
    {
        $this->name = $name;
        $this->type = $inputType;

        $this->setOptions($options);
    }
} 