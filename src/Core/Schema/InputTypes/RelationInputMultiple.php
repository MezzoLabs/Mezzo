<?php


namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;


use Doctrine\DBAL\Types\Type;


class RelationInputMultiple extends RelationInput
{
    protected $doctrineType = Type::SIMPLE_ARRAY;



} 