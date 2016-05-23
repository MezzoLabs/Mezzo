<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Exceptions;


use App\File;

class FileNotSnychronizedException extends FileManagerException
{
    public function __construct(File $file)
    {
        $this->add('The file ' . $file->absolutePath(true) . ' lost the connection to the drive. ' .
            'Delete this file and the database record or update the database record manually to fit the new location on the drive');
    }
}