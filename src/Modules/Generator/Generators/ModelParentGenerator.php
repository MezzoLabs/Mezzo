<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Generators;


use MezzoLabs\Mezzo\Core\Files\Files;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Core\Schema\ModelSchemas;
use MezzoLabs\Mezzo\Modules\Generator\Schema\ModelParentSchema;
use MezzoLabs\Mezzo\Modules\Generator\Schema\ModelParentSchemas;


class ModelParentGenerator extends FileGenerator
{


    /**
     * @var ModelSchemas
     */
    private $modelSchemas;

    /**
     * @var ModelParentSchemas
     */
    private $modelParentSchemas;

    /**
     * @param ModelSchemas $schemas
     */
    public function __construct(ModelSchemas $schemas)
    {
        $this->modelSchemas = $schemas;
    }

    /**
     * Run the generator and save the files to the disk.
     *
     * @return mixed
     */
    public function run()
    {
        $this->files()->save();
    }

    /**
     * Creates a collection of File objects. They contain the files name and the content of the generated file.
     *
     * @return Files
     */
    public function files()
    {
        $files = new Files();

        $modelParentSchemas = $this->createModelParentSchemas();

        $modelParentSchemas->each(
            function (ModelParentSchema $schema) use ($files) {
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
        return mezzo()->path()->toMezzoGenerated() . '/ModelParents';
    }

    /**
     * Create a collection of ModelParents`s based on the fiven model schemas
     *
     * @return ModelParentSchemas
     */
    private function createModelParentSchemas()
    {
        if ($this->modelParentSchemas) return $this->modelParentSchemas;

        $modelParents = new ModelParentSchemas();

        /*
         * Go through every model schema and create a model parent schema out of it.
         */
        $this->modelSchemas->each(
            function (ModelSchema $modelSchema) use ($modelParents) {
                $modelParent = new ModelParentSchema($modelSchema);

                $modelParents->put($modelSchema->className(), $modelParent);
            }
        );

        $this->modelParentSchemas = $modelParents;

        return $modelParents;
    }

    /**
     * @return ModelParentSchemas
     */
    public function modelParentSchemas()
    {
        return $this->createModelParentSchemas();
    }
}