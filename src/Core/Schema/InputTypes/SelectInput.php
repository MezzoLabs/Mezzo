<?php

namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;


use Doctrine\DBAL\Types\Type;

class SelectInput extends InputType
{
    protected $doctrineType = Type::STRING;

    protected $variableType = 'string';

    protected $htmlTag = "select";
} 