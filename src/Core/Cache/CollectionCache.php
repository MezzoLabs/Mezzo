<?php


namespace MezzoLabs\Mezzo\Core\Cache;


use Closure;
use Illuminate\Support\Collection;

abstract class CollectionCache
{
    /**
     * @var Collection
     */
    protected static $collection;


    /**
     * Get a existing instance or retrieve one from the singleton cache.
     *
     * @param $key
     * @param callable|Closure $closure
     * @return mixed
     */
    public static function get($key, Closure $closure)
    {
        $collection = static::collection();

        if (!$collection->has($key)) {
            $collection->put($key, $closure());
        }

        return $collection->get($key);

    }


    /**
     * @return Collection
     */
    public static function collection()
    {
        if (!static::$collection) {
            static::$collection = new Collection();
        }

        return static::$collection;
    }

}