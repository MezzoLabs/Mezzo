<?php

namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\ValidationRules;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;


interface ValidationRule
{
    /**
     * Execute this validation rule and check if the given attribute fits this rule.
     *
     * @return bool
     */
    public function execute() : bool;

    /**
     * @return string
     */
    public function attribute() : string;

    /**
     * @return mixed
     */
    public function value();

    /**
     * @return array
     */
    public function parameters() : array;

    /**
     * @return ValidatorContract
     */
    public function validator() : ValidatorContract;

    /**
     * Registers this validation rule at the laravel validator.
     *
     * @return void
     */
    public static function register();
}