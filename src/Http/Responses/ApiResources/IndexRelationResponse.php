<?php

namespace Mezzolabs\Mezzo\Http\Responses\ApiResources;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Dingo\Api\Http\Response as DingoResponse;

class IndexRelationResponse extends IndexResponse
{
    /**
     * @var EloquentRelation
     */
    protected $eloquentRelation;

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function models($columns = ['*'])
    {
        return $this->repository->relationshipItems($this->eloquentRelation, $columns, $this->query);
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
     * @return EloquentRelation
     */
    public function getEloquentRelation()
    {
        return $this->eloquentRelation;
    }

    /**
     * @param EloquentRelation $eloquentRelation
     */
    public function setEloquentRelation($eloquentRelation)
    {
        $this->eloquentRelation = $eloquentRelation;
    }
}