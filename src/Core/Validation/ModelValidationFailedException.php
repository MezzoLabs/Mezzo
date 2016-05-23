<?php


namespace MezzoLabs\Mezzo\Core\Validation;


use Illuminate\Validation\Validator as IllumniateValidator;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

class ModelValidationFailedException extends MezzoException
{
    public function __construct(MezzoModel $model, IllumniateValidator $validator)
    {
        $this->add("Model validation failed for \"" . class_basename($model) . "\":");


        foreach($validator->messages()->getMessages() as $message){

            $this->add($message[0]);
        }
    }
}