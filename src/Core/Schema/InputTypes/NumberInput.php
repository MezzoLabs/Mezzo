<?php
namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;

use Doctrine\DBAL\Types\Type;

class NumberInput extends SimpleInput
{
    protected $doctrineType = Type::FLOAT;

    protected $htmlTag = "input:number";
}