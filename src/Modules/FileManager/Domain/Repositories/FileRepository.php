<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Domain\Repositories;


use App\File;
use MezzoLabs\Mezzo\Core\Files\Types\FileType;
use MezzoLabs\Mezzo\Core\Helpers\Slug;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles\FileTypesMapper;

class FileRepository extends ModelRepository
{
    protected $model = File::class;

    public static function removeFromDrive($this)
    {

    }

    /**
     * @param array $data
     * @return File
     */
    public function create(array $data)
    {
        $newFile = $this->createFile($data);
        $typeAddon = $this->createTypedFile($newFile);

        return $newFile;
    }

    /**
     *
     * @param array $data
     * @return File
     */
    protected function createFile(array $data)
    {
        return parent::create($this->trimSlashes($data));
    }

    /**
     * @param File $newFile
     * @return \MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles\TypedFileAddon|null
     */
    protected function createTypedFile(File $newFile)
    {
        $fileType = $newFile->fileType();

        $newTypedFile = $this->typedFileAddonInstance($fileType);

        if (!$newTypedFile)
            return null;

        $newTypedFile->file_id = $newFile->id;


        $newTypedFile->save();

        return $newTypedFile;
    }

    protected function typedFileAddonInstance(FileType $fileType)
    {
        $mapper = app()->make(FileTypesMapper::class);

        $fileTypeModel = $mapper->modelInstance($fileType);

        return $fileTypeModel;
    }

    public function findUniqueFileName($fileName, $folder)
    {
        $filesInFolder = $this->filesInFolder($folder, ['filename']);

        if (!$filesInFolder)
            return $fileName;

        $fileNames = $filesInFolder->lists('filename');

        return Slug::findNext($fileName, $fileNames, [
            'separator' => '_',
            'hasExtension' => true
        ]);
    }

    /**
     * @param $folder
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function filesInFolder($folder, $columns = ['*'])
    {
        return $this->query()->where('folder', '=', $folder)->get($columns);
    }

    /**
     * @param $filename
     * @param $folder
     * @return File|null
     */
    public function findByFilenameAndFolder($filename, $folder)
    {
        $folder = trim($folder, '/');

        return $this->query()->where('folder', '=', $folder)->where('filename', '=', $filename)->first();
    }

    /**
     * @return File
     */
    protected function fileInstance()
    {
        return parent::modelInstance();
    }

    protected function trimSlashes($data)
    {
        if (isset($data['folder']))
            $data['folder'] = str_replace('.', '', trim($data['folder']));

        if (isset($data['filename'])) {
            $data['filename'] = trim($data['filename'], '/');
        }

        return $data;
    }


    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return MezzoModel
     * @throws RepositoryException
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return parent::update($this->trimSlashes($data), $id, $attribute);
    }
}