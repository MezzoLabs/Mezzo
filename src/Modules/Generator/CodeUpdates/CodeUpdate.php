<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Generators\CodeUpdates;


use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Modules\Generator\GeneratorException;

abstract class CodeUpdate
{
    /**
     * @var ModelSchema
     */
    protected $model;

    /**
     * @return ModelSchema
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return bool
     * @throws GeneratorException
     */
    protected function validate()
    {
        if (!$this->model)
            throw new GeneratorException('No model set for this code update.');

        if (!class_exists($this->model()->className()))
            throw new GeneratorException($this->model()->className() . ' is not a valid class.');

        return true;
    }

    public function filePath()
    {


    }


}