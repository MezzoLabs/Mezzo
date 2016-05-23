<?php


namespace MezzoLabs\Mezzo\Core\Cache;


use Closure;

class ResourceExistsCache extends CollectionCache
{
    /**
     * @param $table
     * @param $id
     * @param Closure $closure
     */
    public static function checkExistence($table, $id, Closure $closure)
    {
        return static::get($table . '.' . $id, $closure);
    }
}