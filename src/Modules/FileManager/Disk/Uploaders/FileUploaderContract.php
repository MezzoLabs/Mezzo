<?php

namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Uploaders;


use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderContract
{
    /**
     * Returns the default
     *
     * @return Filesystem
     */
    public function fileSystem();

    /**
     * @return string
     */
    public function key() : string;

    /**
     * Upload a file to the disk onto a given path.
     *
     * @param $path
     * @param UploadedFile $file
     * @param \App\File|UploadedFile $file
     * @return bool|mixed
     */
    public function upload($path, UploadedFile $file, \App\File $databaseFile) : bool;
}