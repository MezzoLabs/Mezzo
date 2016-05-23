<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Transformers;


use App\ImageFile;

class ImageFileTransformer extends TypedFileAddonTransformer
{
    protected $modelName = ImageFile::class;

}