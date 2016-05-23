<?php
namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;

use Doctrine\DBAL\Types\Type;

class TextInput extends SimpleInput
{
    protected $doctrineType = Type::STRING;

    protected $htmlTag = "input:text";
}