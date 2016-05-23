<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Migration;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Modules\Generator\Migration\Actions\Actions;

class ChangeSet
{

    /**
     * @var Actions
     */
    protected $actions;

    public function __construct(Actions $actions = null)
    {
        if (!$actions) $actions = new Actions();

        $this->actions = $actions;
    }


    public function checkModelAgainstDatabase(ModelSchema $modelSchema)
    {
        //@TODO-Simon
    }


    public function createAttributes(Collection $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->actions->registerCreate($attribute);
        }
    }

    /**
     * @return Actions
     */
    public function actions()
    {
        return $this->actions;
    }
} 