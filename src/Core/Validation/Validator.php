<?php


namespace MezzoLabs\Mezzo\Core\Validation;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;

class Validator
{
    public static $active = true;

    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Validation\Validator
     * @static
     */
    public static function make($data, $rules, $messages = array(), $customAttributes = array())
    {
        return \Illuminate\Support\Facades\Validator::make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Called whenever a model with validation rules is updated or created.
     *
     * @param MezzoModel|HasValidationRules $model
     */
    public function onSaving($model)
    {

        if (!$model instanceof MezzoModel)
            return;

        if (!$this->isActive()) return true;

        if ($model->permissionsPaused()) {
            return;
        }

        if ($this->permissionGuard()->isActive() && !$this->permissionGuard()->allowsCreateOrEdit($model))
            $this->permissionGuard()->fail('Failed on the second level.');

        $data = (!$model->exists) ? $model->getAttributes() : $model->getDirty();
        $rules = (!$model->exists) ? $model->getRules() : $model->getUpdateRules($model->getDirty());

        foreach ($rules as &$rule) {
            $rule = str_replace(['|confirmed', 'confirmed'], '', $rule);
        }

        $model->validateWithRules($data, $rules, true);

        return;

    }

    /**
     * Called whenever a model with validation rules is deleted.
     *
     * @param MezzoModel|HasValidationRules $model
     * @return bool|void
     */
    public function onDeleting(MezzoModel $model)
    {
        if (!$model instanceof MezzoModel)
            return true;

        if (!$this->isActive()) return true;

        if ($model->permissionsPaused()) {
            return true;
        }

        if ($this->permissionGuard()->isActive() && !$this->permissionGuard()->allowsDelete($model))
            $this->permissionGuard()->fail('Failed on the second level.');

        return true;
    }

    public function permissionGuard()
    {
        return PermissionGuard::make();
    }

    public static function setActive(bool $active)
    {
        static::$active = $active;
    }

    public static function isActive()
    {
        return static::$active;
    }

}