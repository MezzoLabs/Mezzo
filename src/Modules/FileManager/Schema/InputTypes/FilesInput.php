<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes;


use App\File;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputMultiple;

class FilesInput extends RelationInputMultiple
{
    protected $related = File::class;
}