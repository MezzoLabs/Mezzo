<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes;


use App\ImageFile;

class ImageInput extends FileInput
{
    protected $related = ImageFile::class;
}