<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Generators\CodeUpdates;


use MezzoLabs\Mezzo\Core\Schema\ModelSchema;

class AddUseStatement extends CodeUpdate
{
    /**
     * @var string
     */
    private $traitName;

    /**
     * @param ModelSchema $model
     * @param string $traitName
     * @throws \MezzoLabs\Mezzo\Modules\Generator\GeneratorException
     */
    public function __construct(ModelSchema $model, $traitName)
    {
        $this->model = $model;
        $this->traitName = $traitName;

        parent::validate();
    }
}