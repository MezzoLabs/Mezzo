<?php

namespace MezzoLabs\Mezzo\Modules\FileManager\Domain\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;

class ActiveDriveOnlyScope implements ScopeInterface
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('disk', '=', mezzo()->config('filemanager.active_disk'));
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();

        $query->wheres = collect($query->wheres)->reject(function ($where) {
            return $where['type'] == 'where' && $where['column'] == 'disk';
        })->values()->all();
    }
}