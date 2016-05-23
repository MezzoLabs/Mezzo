<?php


namespace MezzoLabs\Mezzo\Modules\User\Http\Transformers;


use App\Role;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;

class RoleTransformer extends EloquentModelTransformer
{

    public function transform($model)
    {
        if(! $model instanceof Role)
            throw new InvalidArgumentException($model);

        return parent::transform($model);
    }
}