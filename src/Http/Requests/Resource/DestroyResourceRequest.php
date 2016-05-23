<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;


class DestroyResourceRequest extends ResourceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->permissionGuard()->allowsDelete($this->currentModelInstance());
    }
}