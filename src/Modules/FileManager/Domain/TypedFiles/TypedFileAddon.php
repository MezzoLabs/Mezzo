<?php

namespace MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles;


use App\File;
use MezzoLabs\Mezzo\Core\Files\Types\FileType;

/**
 * Interface TypedFileAddon
 * @package MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles
 *
 * @property \App\File $file
 */
interface TypedFileAddon
{
    /**
     * @param File $file
     * @return null|TypedFileAddon
     */
    public static function findByFile(File $file);

    /**
     * @return FileType
     */
    public function fileType();
}