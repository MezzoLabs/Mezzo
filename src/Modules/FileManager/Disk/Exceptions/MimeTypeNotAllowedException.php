<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions;


class MimeTypeNotAllowedException extends FileUploadException
{
    public function __construct($triedMimeType)
    {
        $this->message = 'The mime type ' . $triedMimeType . ' is not allowed.';
    }
}