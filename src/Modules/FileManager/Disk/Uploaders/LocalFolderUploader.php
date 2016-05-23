<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Uploaders;


use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\Filesystem as IlluminateFileSystem;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\LocalDisk;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalFolderUploader extends AbstractFileUploader
{


    /**
     * @var LocalDisk
     */
    private $localDisk;

    /**
     * @param LocalDisk $localDisk
     */
    public function __construct(LocalDisk $localDisk)
    {
        $this->localDisk = $localDisk;
    }

    /**
     * Returns the default
     *
     * @return Filesystem
     */
    public function fileSystem()
    {
        return mezzo()->make(IlluminateFileSystem::class);
    }

    /**
     * Upload a file to the disk onto a given path.
     *
     * @param $path
     * @param UploadedFile $file
     * @return bool
     */
    public function upload($path, UploadedFile $file, \App\File $databaseFile) : bool
    {
        return $this->moveFile($file, $path);
    }

    /**
     * Move the uploaded file to its destination
     *
     * @param UploadedFile $file
     * @param $path
     * @return bool
     */
    protected function moveFile(UploadedFile $file, $path) : bool
    {
        $absolute = StringHelper::path($this->localDisk->folder(), $path);

        $this->localDisk->makeDirectory(dirname($absolute));

        $saved = $file->move(dirname($absolute), basename($absolute));
        return $saved == true;
    }

    public function key() : string
    {
        return 'local';
    }
}