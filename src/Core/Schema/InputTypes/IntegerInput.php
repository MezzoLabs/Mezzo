<?php
namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;

use Doctrine\DBAL\Types\Type;

class IntegerInput extends NumberInput
{
    /**
     * @var string
     */
    protected $doctrineType = Type::INTEGER;

}