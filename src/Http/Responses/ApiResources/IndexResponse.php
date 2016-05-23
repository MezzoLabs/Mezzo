<?php

namespace Mezzolabs\Mezzo\Http\Responses\ApiResources;


use Dingo\Api\Http\Response as DingoResponse;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Http\Controllers\ApiResourceController;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;

class IndexResponse extends ResourceResponse
{
    /**
     * @var QueryObject
     */
    protected $query;

    public function dingoResponse() : DingoResponse
    {
        $this->beforeResponse();

        $response = $this->response()->collection($this->models(), $this->bestModelTransformer());

        if ($this->hasPagination()) {
            $this->addPagination($response);
        }

        $this->afterResponse();

        return $response;
    }

    public function addPagination(DingoResponse $response)
    {
        $response->withHeader('X-Total-Count', $this->repository->count($this->query));
    }

    public function hasPagination()
    {
        return !$this->query->pagination()->isEmpty();
    }

    public function models($columns = ['*'])
    {
        return $this->repository->all($columns, $this->query);
    }


    public function beforeResponse()
    {
        event('mezzo.api.indexing: ' . $this->modelReflection()->className(), [$this]);
    }

    public function afterResponse()
    {
        event('mezzo.api.indexed: ' . $this->modelReflection()->className(), [$this]);
    }

    /**
     * @return QueryObject
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param QueryObject $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

}