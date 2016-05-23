<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Http\Transformers;


use App\Content;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;

class ContentTransformer extends EloquentModelTransformer
{
    /**
     * @var string
     */
    protected $modelName = Content::class;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'blocks'
    ];

    protected $defaultIncludes = [
        'blocks'
    ];

    public function transform($model)
    {
        if (!$model instanceof Content)
            throw new InvalidArgumentException($model);


        return parent::transform($model);

    }

    public function includeBlocks(Content $content)
    {
        return $this->automaticCollection($content->blocks);
    }
}