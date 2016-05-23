<?php


namespace MezzoLabs\Mezzo\Exceptions;


use Illuminate\Validation\Validator;

class MezzoException extends \Exception
{
    /**
     * Add a line of text to the message
     *
     * @param $string
     */
    protected function add($string = "")
    {
        $this->message .= $string . "\n";
    }

    protected function getCallingFunction()
    {
        return $this->getTraceEnd()['function'];
    }

    /**
     * @return array
     */
    protected function getTraceEnd()
    {
        return $this->getTrace()[0];
    }

    protected function validationMessages(Validator $validator)
    {
        $this->message = "Validation failed: ";

        $this->message .= implode('. ', $validator->messages()->all());
    }
}