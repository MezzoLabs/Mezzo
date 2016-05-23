<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk\Systems;


use Illuminate\Contracts\Filesystem\Filesystem;

interface DiskSystemContract
{
    /**
     * Returns the according Illuminate filesystem for this disk.
     *
     * @return Filesystem
     */
    public function fileSystem();


    /**
     * Move a file from one path to another.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function move(string $from, string $to) : bool;

    /**
     * Remove a file from this path.
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * Check if there is a file on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path) : bool;

    /**
     * Returns the absolute path of a file.
     * This is needed when you want a base folder that doesnt appear in the database representation.
     *
     * @param string $path
     * @return string
     */
    public function absolutePath(string $path) : string;

    public function sourcePath(string $path) : string;

    /**
     * Returns the public http directory.
     *
     * @return string
     */
    public function baseUrl() : string;


        /**
     * A unqique key for this disk.
     *
     * @return string
     */
    public function key() : string;



}