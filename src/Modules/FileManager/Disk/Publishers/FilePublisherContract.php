<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Publishers;


use MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems\DiskSystemContract;

interface FilePublisherContract
{
    /**
     * The underlying file disk system.
     *
     * @return DiskSystemContract
     */
    public function system() : DiskSystemContract;

    /**
     * @return bool
     */
    public function publish();
}