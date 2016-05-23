<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateResourceRequest extends UpdateOrStoreResourceRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isTemplateRequest()) {
            return true;
        }

        if (!$this->hasValidModelInstance()) {
            throw new NotFoundHttpException();
        }

        return $this->permissionGuard()->allowsEdit($this->currentModelInstance());
    }

    /**
     * @return int
     */
    protected function id()
    {
        return intval($this->route('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->formObject()->rulesForUpdating(array_keys($this->all()));
    }

    protected function value($key)
    {
        if ($this->has($key))
            return $this->offsetGet($key);


        $model = $this->currentModelInstance();
        $attributes = $model->getAttributes();

        if (!isset($attributes[$key]))
            throw new BadRequestHttpException('The request or the resource needs the key ' . $key);

        return $attributes[$key];
    }
}