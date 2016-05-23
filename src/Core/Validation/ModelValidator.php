<?php


namespace MezzoLabs\Mezzo\Core\Validation;


use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as IlluminateValidationFactory;
use Illuminate\Validation\Validator as IlluminateValidator;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;

class ModelValidator
{
    /**
     * @var IlluminateValidator
     */
    protected $illuminateValidator;

    /**
     * @param MezzoModelReflection $mezzoModelReflection
     * @param array $data
     * @param array $overwriteRules
     */
    public function __construct(MezzoModelReflection $mezzoModelReflection, $data = [], $overwriteRules = [])
    {
        $rules = new Collection($mezzoModelReflection->rules());
        $rules = $rules->merge($overwriteRules);

        $validationFactory = app()->make(IlluminateValidationFactory::class);

        $this->illuminateValidator = $validationFactory->make($data, $rules->toArray());
    }

    /**
     * @return IlluminateValidator
     */
    public function illuminateValidator()
    {
        return $this->illuminateValidator;
    }

    public function passes()
    {
        return $this->illuminateValidator()->passes();
    }

    public function fails()
    {
        return !$this->passes();
    }

}