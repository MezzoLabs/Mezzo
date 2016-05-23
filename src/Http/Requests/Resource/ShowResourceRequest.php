<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowResourceRequest extends ResourceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!$this->hasValidModelInstance()) {
            throw new NotFoundHttpException(trans('mezzo.messages.resource_not_found'));
        }


        return $this->permissionGuard()->allowsShow($this->currentModelInstance());
    }
}