<?php


namespace MezzoLabs\Mezzo\Modules\Addresses\Http\Transformers;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Http\Transformers\Plugins\TransformerPlugin;

class DistanceTransformerPlugin extends TransformerPlugin
{

    /**
     * @param MezzoModel $model
     * @return array
     */
    public function transform(MezzoModel $model) : array
    {
        if (!array_has($model->getAttributes(), 'distance'))
            return [];

        return [
            'distance' => $model->getAttribute('distance')
        ];
    }
}