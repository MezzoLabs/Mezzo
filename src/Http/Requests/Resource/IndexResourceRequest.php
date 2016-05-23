<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;


class IndexResourceRequest extends ResourceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->permissionGuard()->allowsShow($this->modelReflection()->instance());
    }


}