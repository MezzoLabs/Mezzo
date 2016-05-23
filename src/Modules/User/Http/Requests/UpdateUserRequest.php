<?php


namespace MezzoLabs\Mezzo\Modules\User\Http\Requests;

use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;

class UpdateUserRequest extends UpdateResourceRequest
{
    use StoresOrUpdatesUser;

    public function processData()
    {
        parent::processData();

        // Do not touch the password if it is empty.
        if(empty($this->offsetGet('password'))){
            $this->offsetUnset('password');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        return $this->unsetPasswordConfirmationRules($rules);
    }

    public function all()
    {
        $all = parent::all();

        return $all;
    }

}