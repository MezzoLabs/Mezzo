<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Models;

use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Collection\StrictCollection;

class MezzoEloquentCollection extends StrictCollection
{
    /**
     * @var Collection
     */
    protected $collection;

    public function asList()
    {
        if ($this->isEmpty())
            return [];

        $list = new Collection();
        $this->each(function (MezzoModel $model) use ($list) {
            $list->put($model->id, $model->id . ' - ' . $model->getLabelAttribute());
        });

        return $list;
    }

    /**
     * @return MezzoModel
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * @return Collection
     */
    public function eloquentCollection()
    {
        return $this->collection;
    }

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof MezzoModel;
    }
}