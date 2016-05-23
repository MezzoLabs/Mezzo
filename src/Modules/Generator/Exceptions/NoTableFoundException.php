<?php

namespace MezzoLabs\Mezzo\Modules\Generator;


use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;

class NoTableFoundException extends GeneratorException
{

    public function __construct(Attribute $attribute)
    {
        $this->add('Cannot work with ' . $attribute->name() . '. It has no table or model set.');
    }

} 