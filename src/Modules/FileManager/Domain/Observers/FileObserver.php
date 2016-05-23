<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Domain\Observers;


use App\File;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\DisksManager;
use MezzoLabs\Mezzo\Modules\FileManager\Exceptions\FileManagerException;
use MezzoLabs\Mezzo\Modules\FileManager\Exceptions\FileNotSnychronizedException;
use MezzoLabs\Mezzo\Modules\FileManager\Exceptions\FileNotSynchronized;
use MezzoLabs\Mezzo\Modules\FileManager\Exceptions\FileNotSynchronizedException;

class FileObserver
{
    /**
     * Triggered before a file gets deleted in the database.
     *
     * @param File $file
     * @return bool
     */
    public function deleting(File $file)
    {
        if(! $file->existsOnDrive())
            return true;


        return $this->disks()->deleteFile($file->shortPath(), $file->getAttribute('disk'));
    }

    protected function disks(){
        return app(DisksManager::class);
    }

    /**
     * Triggered before a file is updated inside the database.
     *
     * @param File $file
     * @return bool|void
     * @throws FileNotSnychronizedException
     */
    public function updating(File $file)
    {
        if($file->isDirty('disk'))
            throw new FileManagerException('You cannot change the disk of a file.');

        if(! $file->existsOnDrive(true))
            throw new FileNotSnychronizedException($file);

        if(!$file->isDirty('filename', 'folder'))
            return true;

        $fromPath = StringHelper::path($file->getOriginal('folder'), $file->getOriginal('filename'));
        $toPath = StringHelper::path($file->getAttribute('folder'), $file->getAttribute('filename'));

        return $this->disks()->moveFile($fromPath, $toPath, $file->getAttribute('disk'));
    }

}