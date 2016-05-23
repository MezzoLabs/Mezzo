<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;


class StoreResourceRequest extends UpdateOrStoreResourceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->permissionGuard()->allowsCreate($this->newModelInstance());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->formObject();

        return $this->formObject()->rulesForStoring();
    }
}