<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes;


use App\File;
use Doctrine\DBAL\Types\Type;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputSingle;

class FileInput extends RelationInputSingle
{
    protected $doctrineType = Type::INTEGER;

    protected $related = File::class;
}