<?php


namespace MezzoLabs\Mezzo\Http\Transformers;


class GenericEloquentModelTransformer extends EloquentModelTransformer
{

    /**
     * @param string $modelName
     */
    public function __construct($modelName)
    {
        parent::__construct($modelName);
    }
}