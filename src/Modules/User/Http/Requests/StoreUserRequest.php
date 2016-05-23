<?php


namespace MezzoLabs\Mezzo\Modules\User\Http\Requests;


use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;

class StoreUserRequest extends StoreResourceRequest
{
    use StoresOrUpdatesUser;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules = $this->unsetPasswordConfirmationRules($rules);
        $rules = $this->setPasswordRulesForCreate($rules);

        return $rules;
    }

}