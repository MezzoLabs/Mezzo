<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Generators;


use MezzoLabs\Mezzo\Core\Files\Files;
use MezzoLabs\Mezzo\Modules\Generator\Migration\ChangeSet;
use MezzoLabs\Mezzo\Modules\Generator\Schema\MigrationAction;
use MezzoLabs\Mezzo\Modules\Generator\Schema\MigrationSchema;
use MezzoLabs\Mezzo\Modules\Generator\Schema\MigrationSchemas;

class MigrationGenerator extends FileGenerator
{

    /**
     * @var ChangeSet
     */
    protected $changeSet;

    /**
     * Create a Generator instance for creating migration files based on a change set of attributes.
     *
     * @param ChangeSet $changeSet
     */
    public function __construct(ChangeSet $changeSet)
    {
        $this->changeSet = $changeSet;
    }

    /**
     * @return MigrationSchemas
     */
    public function createMigrationsSchema()
    {
        $migrationsSchema = new MigrationSchemas();
        $migrationsSchema->addChangeSet($this->changeSet);

        return $migrationsSchema;
    }


    /**
     * Run the generator and save the files to the disk.
     *
     * @return mixed
     */
    public function run()
    {
        return $this->files()->save();
    }

    /**
     * Creates a collection of File objects. They contain the files name and the content of the generated file.
     *
     * @return Files
     */
    public function files()
    {
        $files = new Files();

        $migrationsSchema = $this->createMigrationsSchema();

        $migrationsSchema->each(
            function (MigrationSchema $schema) use ($files) {
                $newFile = $schema->file($this->folderName());
                $files->addFile($newFile);
            });

        return $files;
    }


    /**
     * The name of the folder in which the files are created.
     *
     * @return string
     */
    public function folderName()
    {
        return mezzo()->path()->toDatabaseDirectory() . '/migrations';
    }

    /**
     * @return ChangeSet
     */
    public function changeSet()
    {
        return $this->changeSet;
    }
}