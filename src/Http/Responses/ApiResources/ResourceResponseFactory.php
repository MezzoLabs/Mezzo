<?php

namespace Mezzolabs\Mezzo\Http\Responses\ApiResources;

use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Dingo\Api\Http\Response as DingoResponse;


class ResourceResponseFactory
{
    public function indexRelation(EloquentRelation $eloquentRelation, QueryObject $queryObject, ModelRepository $repository) : DingoResponse
    {
        return $this->makeIndexRelationResponse($eloquentRelation,$queryObject, $repository)->dingoResponse();
    }

    public function makeIndexRelationResponse(EloquentRelation $eloquentRelation, QueryObject $queryObject, ModelRepository $repository) : IndexRelationResponse
    {
        $indexRelationResponse = new IndexRelationResponse();
        $indexRelationResponse->setEloquentRelation($eloquentRelation);
        $indexRelationResponse->setQuery($queryObject);
        $indexRelationResponse->setRepository($repository);

        return $indexRelationResponse;
    }

    public function indexResource(QueryObject $query, ModelRepository $repository) : DingoResponse
    {
        return $this->makeIndexResourceResponse($query,$repository)->dingoResponse();
    }

    public function makeIndexResourceResponse(QueryObject $query, ModelRepository $repository) : IndexResponse
    {
        $indexResourceResponse = new IndexResponse();
        $indexResourceResponse->setQuery($query);
        $indexResourceResponse->setRepository($repository);


        return $indexResourceResponse;
    }
}