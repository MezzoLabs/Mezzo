<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Domain\Repositories;


use App\ImageFile;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;

class ImageFileRepository extends ModelRepository
{
    protected $model = ImageFile::class;



}