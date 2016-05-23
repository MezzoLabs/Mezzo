<?php
namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;

use Doctrine\DBAL\Types\Type;

abstract class BooleanInput extends SimpleInput
{
    protected $doctrineType = Type::BOOLEAN;
}