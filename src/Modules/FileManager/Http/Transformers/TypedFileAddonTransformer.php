<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Transformers;


use InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;
use MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles\TypedFileAddon;

class TypedFileAddonTransformer extends EloquentModelTransformer
{
    /**
     * @var string
     */
    protected $modelName = File::class;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [

    ];

    protected $defaultIncludes = [

    ];

    public function transform($model)
    {
        if (!$model instanceof TypedFileAddon)
            throw new InvalidArgumentException();


        $array = parent::transform($model);

        $file = $model->file()->first();

        if ($file) {
            $array['url'] = $file->url();
        }

        return $array;
    }
}