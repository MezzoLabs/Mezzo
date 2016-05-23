<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories;


use Illuminate\Support\Collection;
use Mezzolabs\Mezzo\Cockpit\Http\FormObjects\NestedRelation;
use Mezzolabs\Mezzo\Cockpit\Http\FormObjects\NestedRelations;

class NestedRelationsProcessor
{
    /**
     * @var NestedRelations
     */
    protected $nestedRelations;

    /**
     * Stores the ids of the updated and the created relations.
     *
     * @var array
     */
    protected $ids = [];

    public function __construct(NestedRelations $nestedRelations)
    {
        $this->nestedRelations = $nestedRelations;
    }

    public function updateOrCreateBefore()
    {
        $this->ids = [];


        /** @var NestedRelation $nestedRelation */
        foreach ($this->nestedRelations->savesBeforeParentIsCreated() as $nestedRelation) {
            $this->ids[$nestedRelation->parentAttributeName()] = $this->updateOrCreateRelation($nestedRelation);
        }

        return $this->ids;
    }

    public function updateOrCreateAfter($parentId)
    {
        /** @var NestedRelation $nestedRelation */
        foreach ($this->nestedRelations->savesAfterParentIsCreated() as $nestedRelation) {
            if($nestedRelation->isEmpty()) continue;

            $nestedRelation->setParentId($parentId);
            $this->updateOrCreateRelation($nestedRelation);
        }
    }

    protected function updateOrCreateRelation(NestedRelation $nestedRelation)
    {
        if ($nestedRelation->isEmpty())
            return false;

        if ($nestedRelation->hasOneChild()) {
            $model = $this->updateOrCreateSingleChild($nestedRelation->repository(), $nestedRelation->data());
            return $model->id;
        }

        $ids = $this->updateOrCreateMultipleChildren($nestedRelation->repository(), $nestedRelation->data());
        return $ids;
    }

    /**
     * @param ModelRepository $repository
     * @param Collection $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function updateOrCreateSingleChild(ModelRepository $repository, Collection $data)
    {
        $id = $data->get('id', '');

        if (empty($id)) {
            return $repository->create($data->toArray());
        }

        return $repository->update($data->toArray(), $id);
    }

    /**
     * @param ModelRepository $repository
     * @param Collection $dataArray
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function updateOrCreateMultipleChildren(ModelRepository $repository, Collection $dataArray)
    {
        $ids = [];

        foreach ($dataArray as $values) {
            $model = $this->updateOrCreateSingleChild($repository, new Collection($values));
            $ids[] = $model->id;
        }

        return $ids;
    }

    public function ids()
    {
        return $this->ids;
    }
}