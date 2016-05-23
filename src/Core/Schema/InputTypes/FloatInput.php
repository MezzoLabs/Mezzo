<?php
namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;

use Doctrine\DBAL\Types\Type;

class FloatInput extends NumberInput
{
    /**
     * @var string
     */
    protected $doctrineType = Type::FLOAT;

    protected $htmlTag = "input:number";

    protected $htmlAttributes = [
        'step' => '0.000000001'
    ];


}