<?php

namespace MezzoLabs\Mezzo\Modules\Posts\Http\Transformers;


use App\Post;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;

class PostTransformer extends EloquentModelTransformer
{
    public function transform($model)
    {
        if (!$model instanceof Post)
            throw new InvalidArgumentException($model);

        $array = parent::transform($model);

        $array['_is_published'] = $model->isPublished();

        return $array;
    }
}