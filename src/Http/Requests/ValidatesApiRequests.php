<?php


namespace MezzoLabs\Mezzo\Http\Requests;


use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Contracts\Validation\Validator;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ValidatesApiRequests
{
    /**
     * Handle a failed API validation attempt.
     *
     * @param Validator|\Illuminate\Validation\Validator $validator
     * @return mixed
     */
    protected function failedApiValidation(Validator $validator)
    {
        if ($this instanceof StoreResourceRequest)
            throw new StoreResourceFailedException('Could not create new ' . $this->modelReflection()->name() . '.', $validator->errors());

        if ($this instanceof UpdateResourceRequest)
            throw new UpdateResourceFailedException('Could not update ' . $this->modelReflection()->name() . '.', $validator->errors());

        throw new HttpException('Unknown validation error.');
    }

    protected function failedApiAuthorization()
    {
        throw new AccessDeniedHttpException();
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection
     */
    abstract function modelReflection();
}