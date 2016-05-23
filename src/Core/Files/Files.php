<?php


namespace MezzoLabs\Mezzo\Core\Files;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Modules\Generator\GeneratorException;

class Files extends Collection
{

    /**
     * Add a file to the file collection.
     *
     * @param File $file
     * @throws GeneratorException
     */
    public function addFile(File $file)
    {
        if ($this->has($file->filename())) {
            throw new GeneratorException('File is already existing: ' . $file->filename());
        }

        $this->put($file->filename(), $file);
    }

    /**
     * Save all the files in this collection to the disk.
     */
    public function save()
    {
        $this->each(function (File $file) {
            $file->save();
        });
    }
} 