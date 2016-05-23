<?php


namespace MezzoLabs\Mezzo\Http\Transformers\Plugins;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;

class LabelTransformerPlugin extends TransformerPlugin
{
    /**
     * @param MezzoModel $model
     * @return array
     */
    public function transform(MezzoModel $model) : array
    {
        return ['_label' => $model->label];
    }
}