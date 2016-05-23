<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\ValidationRules;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;


abstract class AbstractValidationRule implements ValidationRule
{
    const KEY = "";

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var ValidatorContract
     */
    protected $validator;

    //
    public function __construct(string $attribute, $value, array $parameters, ValidatorContract $validator)
    {
        $this->attribute = $attribute;
        $this->value = $value;
        $this->parameters = $parameters;
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    public function attribute() : string
    {
        return $this->attribute;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function parameters() : array
    {
        return $this->parameters;
    }

    /**
     * @return ValidatorContract
     */
    public function validator() : ValidatorContract
    {
        return $this->validator;
    }


    public static function register()
    {
        if (empty(static::KEY)) {
            throw new \Exception('A validation rule needs a valid key.');
        }

        Validator::extend(static::KEY, function ($attribute, $value, $parameters, $validator) {
            return (new static($attribute, $value, $parameters, $validator))->execute();
        });
    }
}