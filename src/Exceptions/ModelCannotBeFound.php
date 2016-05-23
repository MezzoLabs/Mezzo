<?php


namespace MezzoLabs\Mezzo\Exceptions;


class ModelCannotBeFound extends \Exception
{

    /**
     * @param string $model
     */
    public function  __construct($model)
    {
        $this->message = 'The model ' . $model . ' cannot be found .' .
            '. The Reflector wasn\'t able to find the model. ' .
            'It should be located inside the app directory and use the Mezzo trait.';
    }
} 