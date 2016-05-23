<?php


namespace MezzoLabs\Mezzo\Modules\User\Http\Requests;


trait StoresOrUpdatesUser
{
    protected function unsetPasswordConfirmationRules($rules) : array
    {
        if (isset($rules['password']))
            $rules['password'] = str_replace(['|confirmed', 'confirmed'], '', $rules['password']);

        if (isset($rules['password_confirmation']))
            unset($rules['password_confirmation']);

        return $rules;
    }

    protected function setPasswordRulesForCreate($rules) : array
    {
        $rules['password'] = str_replace('sometimes', 'required', $rules['password']);

        return $rules;
    }

}