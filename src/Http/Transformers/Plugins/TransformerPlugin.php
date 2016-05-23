<?php


namespace MezzoLabs\Mezzo\Http\Transformers\Plugins;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;

abstract class TransformerPlugin
{
    /**
     * @param MezzoModel $model
     * @return array
     */
    abstract public function transform(MezzoModel $model) : array;
}