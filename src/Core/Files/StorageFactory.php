<?php


namespace MezzoLabs\Mezzo\Core\Files;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

class StorageFactory
{
    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function local()
    {
        return static::fileSystemManager()->disk('local');
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    public static function root()
    {
        return mezzo()->make(Filesystem::class);
    }

    /**
     * @return FilesystemManager
     */
    protected static function fileSystemManager()
    {
        return mezzo()->make(FilesystemManager::class);
    }
} 