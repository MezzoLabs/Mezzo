<?php

namespace Mezzolabs\Mezzo\Http\Responses\ApiResources;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;
use Dingo\Api\Http\Response as DingoResponse;

abstract class ResourceResponse
{
    /**
     * @var ModelRepository
     */
    protected $repository;

    /**
     * Use the factories
     *
     * ResourceResponse constructor.
     */
    final public function __construct()
    {

    }

    /**
     * Generate a dingo response out of the given parameters.
     *
     * @return DingoResponse
     */
    abstract public function dingoResponse() : DingoResponse;

    /**
     * @return ModelRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param ModelRepository $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return ApiResponseFactory
     */
    protected function response()
    {
        return mezzo()->make(ApiResponseFactory::class);
    }

    protected function bestModelTransformer()
    {
        return EloquentModelTransformer::makeBest($this->modelReflection()->className());
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection
     */
    public function modelReflection()
    {
        return $this->repository->modelReflection();
    }


    public function beforeResponse()
    {
        event('mezzo.api.generic.before: ' . $this->modelReflection()->className(), [$this]);
    }

    public function afterResponse()
    {
        event('mezzo.api.generic.after: ' . $this->modelReflection()->className(), [$this]);
    }

}