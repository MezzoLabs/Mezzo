<?php


namespace MezzoLabs\Mezzo\Exceptions;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;

class ModelCannotBeAssociated extends MezzoException
{

    /**
     * @param string $model
     * @param ModuleProvider $module
     */
    public function  __construct($model, ModuleProvider $module)
    {
        if (is_object($model) && get_class($model) == ModelReflection::class) $model = $model->className();

        $this->add("The model " . $model . " cannot be grabbed by the module " . get_class($module) . ".");
        $this->add("The Reflector wasnt able to find the model.");
        $this->add("It should be located inside the app directory and use the Mezzo trait.");
        $this->add();
        $this->add("Here is a list of all possible models:");

        foreach (mezzo()->moduleCenter()->reflector()->reflections() as $reflection) {
            $this->add($reflection->className());
        }
    }
} 