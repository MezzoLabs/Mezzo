<?php


namespace MezzoLabs\Mezzo\Core\Validation;
use MezzoLabs\Mezzo\Http\Requests\Request;
use MezzoLabs\Mezzo\Modules\Posts\Domain\Observers\UserObserver;

/**
 * Class HasValidationRules
 * @package MezzoLabs\Mezzo\Core\Validation
 *
 * @property array $rules
 */
trait HasValidationRules
{

    protected static $permissionsPaused = false;

    public static function bootHasValidationRules()
    {
        app()['events']->listen('eloquent.saving*', \MezzoLabs\Mezzo\Core\Validation\Validator::class . '@onSaving');
        app()['events']->listen('eloquent.deleting*', \MezzoLabs\Mezzo\Core\Validation\Validator::class . '@onDeleting');

    }


    public function validateOrFail($data = [], $mode = "create")
    {
        if($mode == "create")
            return $this->validateCreate($data, true);

        return $this->validateUpdate($data, true);
    }

    public function validateCreate($data = [], $orFail = true)
    {
        return $this->validateWithRules($data, $this->getRules(), $orFail);
    }

    /**
     * @param array $data
     * @param array $rules
     * @param bool $orFail
     * @return \Illuminate\Validation\Validator
     * @throws ModelValidationFailedException
     */
    public function validateWithRules($data = [], $rules = [], $orFail = true)
    {
        $validator = $this->validator($data, $rules);

        if($orFail && $validator->fails()){
            throw new ModelValidationFailedException($this, $validator);
        }

        return $validator;
    }

    private function defaultData()
    {
        return Request::allInput();
    }

    /**
     * Create a new Validator instance.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return \Illuminate\Validation\Validator
     */
    protected function validator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $factory = app()->make(\Illuminate\Validation\Factory::class);

        return $factory->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function validateUpdate($data = [], $orFail = true)
    {
        return $this->validateWithRules($data, $this->getUpdateRules($data), $orFail);
    }

    /**
     * Remove "required" rules for partially updates.
     *
     * @param array $data
     * @return array
     */
    public function getUpdateRules(array $data)
    {
        $rulesTransformer = new RulesTransformer($this->getRules(), $this->id);
        return $rulesTransformer->rulesForUpdating(array_keys($data));
    }


    public static function pausePermissions()
    {
        static::$permissionsPaused = true;
    }

    public static function unpausePermissions()
    {
        static::$permissionsPaused = false;
    }

    public static function permissionsPaused() : bool
    {
        return static::$permissionsPaused;
    }


}