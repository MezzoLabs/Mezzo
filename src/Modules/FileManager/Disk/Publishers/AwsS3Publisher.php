<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers;


use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\AwsS3Disk;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\DiskSystemContract;

class AwsS3Publisher extends AbstractFilePublisher implements FilePublisherContract
{

    /**
     * The underlying file disk system.
     *
     * @return DiskSystemContract
     */
    public function system() : DiskSystemContract
    {
        return app(AwsS3Disk::class);
    }

    public function publish()
    {
        return \Redirect::to($this->file()->sourcePath());
    }
}