<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Transformers;


use App\File;
use App\Tutorial;
use App\User;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\CarbonTransformer;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;

class FileTransFormer extends EloquentModelTransformer
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
        if(! $model instanceof File)
            throw new InvalidArgumentException($model);

        $carbonTransformer = app(CarbonTransformer::class);

        $typeAddon = $model->typeAddon();

        $typeAddonInfo = [];

        if($typeAddon){
            $typeAddonInfo = $typeAddon->toArray();
            $typeAddonInfo['_model'] = mezzo()->model($typeAddon)->name();
        }

        return [
            'id' => $model->id,
            'path' => $model->shortPath(),
            'filename' => $model->filename,
            'extension' => $model->extension,
            'disk' => $model->disk,
            'url' => $model->url(),
            'info' => json_decode($model->info),
            'type' => $model->fileType()->name(),
            'addon' => $typeAddonInfo,
            'created_at' => $carbonTransformer->transform($model->created_at),
            'updated_at' => $carbonTransformer->transform($model->updated_at),

        ];
    }
}