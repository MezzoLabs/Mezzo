<?php


namespace MezzoLabs\Mezzo\Exceptions;


class InvalidModelException extends \InvalidArgumentException
{

    /**
     * You can only make a modelWrapper out of a class name (string) or out of an existing modelWrapper
     *
     * @param string $notAModel
     * @internal param ModelWrapper $model
     * @internal param ModuleProvider $module
     */
    public function  __construct($notAModel)
    {
        if(!is_string($notAModel))
            $notAModel = get_class($notAModel);

        $this->message = $notAModel . ' is not a model.';
    }
} 