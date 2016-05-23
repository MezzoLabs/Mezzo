<?php


namespace MezzoLabs\Mezzo\Core\Cache;


use Closure;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use ReflectionClass;

class Singleton
{
    /**
     * @var Collection
     */
    private static $instances;


    /**
     * Get a existing instance or retrieve one from the singleton cache.
     *
     * @param $key
     * @param callable|Closure $closure
     * @return mixed
     */
    public static function get($key, Closure $closure)
    {
        $singletons = static::instances();

        if (!$singletons->has($key)) {
            $singletons->put($key, $closure());
        }

        return $singletons->get($key);

    }

    /**
     * Gives you the singleton instance of a class reflection.
     *
     * @param $class
     * @return ReflectionClass
     */
    public static function reflection($class)
    {
        if (is_object($class))
            $class = get_class($class);

        return Singleton::get('reflection.' . $class, function () use ($class) {
            return new \ReflectionClass($class);
        });

    }

    /**
     * Gives you the singleton instance of a object reflection.
     *
     * @param $object
     * @return ReflectionClass
     * @throws InvalidArgumentException
     */
    public static function reflectionObject($object)
    {
        if (!is_object($object))
            throw new InvalidArgumentException($object);

        return Singleton::get('reflectionObject.' . get_class($object), function () use ($object) {
            return new \ReflectionObject($object);
        });

    }

    public static function instances()
    {
        if (!static::$instances) {
            static::$instances = new Collection();
        }

        return static::$instances;
    }


} 