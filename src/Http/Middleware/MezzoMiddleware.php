<?php


namespace MezzoLabs\Mezzo\Http\Middleware;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\MezzoMiddlewareException;


abstract class MezzoMiddleware
{
    protected $key = "mezzo.middleware_key";

    /**
     * @return MezzoMiddleware
     */
    public static function make()
    {
        return app()->make(static::class);
    }

    /**
     * @return $this
     */
    public function registerAtRouter()
    {
        mezzo()->makeRouter()->middleware($this);
    }

    /**
     * @return string
     * @throws MezzoMiddlewareException
     */
    public function key()
    {
        if ($this->key === "mezzo.middleware_key")
            throw new MezzoMiddlewareException('You have to set a key for the middleware ' . get_class($this));

        if (strpos($this->key, 'mezzo.') === -1)
            return 'mezzo.' . $this->key;

        return $this->key;
    }

    /**
     * @param $middlewareClasses
     * @throws InvalidArgumentException
     */
    public static function register($middlewareClasses)
    {
        $middlewareClasses = new Collection($middlewareClasses);

        foreach($middlewareClasses as $middlewareClass){
            if(!is_string($middlewareClass) || !class_exists($middlewareClass))
                throw new InvalidArgumentException($middlewareClass);

            $mezzoMiddleware = $middlewareClass::make();

            if(!($mezzoMiddleware instanceof MezzoMiddleware))
                throw new InvalidArgumentException($mezzoMiddleware);

            $mezzoMiddleware->registerAtRouter();
        }
    }
}