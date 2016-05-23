<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;


class CreateResourceRequest extends ShowResourceRequest
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
}