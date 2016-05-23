<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories;

use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use MezzoLabs\Mezzo\Core\Cache\ResourceExistsCache;

abstract class EloquentRepository
{

    /**
     * @return \Illuminate\Database\MySqlConnection
     */
    protected function mysqlConnection()
    {
        return app('db');
    }

    /**
     * @param $table
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table($table)
    {
        return $this->mysqlConnection()->table($table);
    }

    /**
     * Check if a row with the given id exists on a certain table.
     *
     * @param $id
     * @param $table
     * @return mixed
     */
    public function exists($id, $table)
    {
        return ResourceExistsCache::checkExistence($table, $id, function () use ($table, $id) {
            return $this->table($table)->where('id', '=', $id)->count() == 1;
        });
    }

    /**
     * @param EloquentCollection $collection
     * @return EloquentCollection
     */
    public function pluckIds(EloquentCollection $collection)
    {
        return $collection->pluck('id');
    }

    /**
     * @return CacheManager|CacheRepository
     */
    public function cache()
    {
        return app('cache');
    }
}