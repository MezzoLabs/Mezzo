<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions;


class MaximumFileSizeExceededException extends FileUploadException
{
    /**
     * @param int $fileSizeBytes
     * @param int $maximumBytes
     */
    public function __construct($fileSizeBytes, $maximumBytes)
    {
        $fileSizeString = round($fileSizeBytes / 1000 / 1000, 2) . ' MB';
        $maximumSizeString = round($maximumBytes / 1000 / 1000, 2) . ' MB';

        $this->message = 'You reached the maximum file size of ' . $maximumSizeString . '. ' .
            'Your file was ' . $fileSizeString . ' big.';
    }
}