<?php
/**
 * Created by: simon.schneider
 * Date: 16.09.2015 - 16:12
 * Project: MezzoDemo
 */


namespace MezzoLabs\Mezzo\Core\Schema\InputTypes;


use Doctrine\DBAL\Types\Type;

class TextArea extends TextInput
{
    protected $doctrineType = Type::TEXT;

    protected $variableType = 'string';

    protected $htmlTag = "textarea";


} 