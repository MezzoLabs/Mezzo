<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Files\Types\FileType;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Modules\FileManager\FileManagerModule;

class FileTypesMapper
{
    /**
     * @var Collection
     */
    protected $lookup;

    public function __construct()
    {
        $this->makeLookup();
    }

    protected function makeLookup()
    {
        $this->lookup = new Collection();

        $allModels = FileManagerModule::make()->models();

        $allModels->each(function(ModelReflection $modelReflection){
            $instance = $modelReflection->instance();
            if(! $instance instanceof TypedFileAddon)
                return true;

            $this->lookup->put(get_class($instance->fileType()), get_class($instance));
        });
    }

    /**
     * @param FileType $fileType
     * @return TypedFileAddon|null
     */
    public function modelInstance(FileType $fileType)
    {
        $class = $this->modelClass($fileType);

        if(!$class) return null;

        return app()->make($class);
    }

    /**
     * @param FileType $fileType
     * @return mixed
     */
    public function modelClass(FileType $fileType)
    {
        return $this->lookup->get(get_class($fileType), null);
    }
}