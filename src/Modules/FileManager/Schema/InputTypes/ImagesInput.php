<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes;


use App\ImageFile;

class ImagesInput extends FilesInput
{
    protected $related = ImageFile::class;
}