<?php

namespace MezzoLabs\Mezzo\Core\Schema\Converters;


use MezzoLabs\Mezzo\Core\Traits\IsShared;

abstract class Converter
{
    use IsShared;

    public function __construct()
    {

    }

    abstract public function run($toConvert);
}