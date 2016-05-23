<?php


namespace MezzoLabs\Mezzo\Core\Routing;

use Closure;
use Illuminate\Routing\Router as LaravelRouter;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Http\Middleware\MezzoMiddleware;


class Router
{
    use CanHaveModule;

    /**
     * @var RoutesGenerator
     */
    protected $generator;

    /**
     * @var ApiRouter
     */
    protected $apiRouter;

    /**
     * @var LaravelRouter
     */
    protected $cockpitRouter;


    /**
     * @param RoutesGenerator $generator
     * @param LaravelRouter $laravelRouter
     * @param ApiRouter $apiRouter
     */
    public function __construct(RoutesGenerator $generator, CockpitRouter $cockpitRouter, ApiRouter $apiRouter)
    {
        $this->generator = $generator;
        $this->apiRouter = $apiRouter;
        $this->cockpitRouter = $cockpitRouter;
    }

    /**
     * @param ModuleProvider|string $module
     * @param array $attributes
     * @param Closure $callback
     */
    public function instance($module, $attributes = ['cockpit' => [], 'api' => []], Closure $callback)
    {
        $attributes = new Collection($attributes);
        $module = mezzo()->module($module);

        /**
         * Create new router instances and set the grouped router.
         * This way you don't have to call the group methods yourself.
         * You can change the attributes of the groups by using the $attributes variable.
         */
        $cockpitRouter = new CockpitRouter($this->cockpitRouter->laravelRouter());
        $apiRouter = new ApiRouter($this->apiRouter->dingoRouter());


        $copy = new Router($this->generator, $cockpitRouter, $apiRouter);
        $copy->setModule($module);

        $cockpitRouter->group($attributes->get('cockpit', []));
        $apiRouter->group($attributes->get('api', []));


        call_user_func($callback, $copy, $copy->apiRouter(), $copy->cockpitRouter());
    }

    /**
     * Return the singleton instance
     *
     * @return Router
     */
    public static function make()
    {
        return mezzo()->make(static::class);
    }

    /**
     * @param Closure $callback
     * @param array $overwriteAttributes
     * @internal param ModuleProvider $module
     */
    public function api(Closure $callback, $overwriteAttributes = [])
    {
        $this->apiRouter->group($overwriteAttributes, $callback);
    }

    /**
     * @return ApiRouter
     */
    public function apiRouter()
    {
        return $this->apiRouter;
    }

    /**
     * @return CockpitRouter
     */
    public function cockpitRouter()
    {
        return $this->cockpitRouter;
    }

    /**
     * @return RoutesGenerator
     */
    public function generator()
    {
        return $this->generator;
    }

    /**
     * Add a named middleware to the routes.
     *
     * @param MezzoMiddleware $mezzoMiddleware
     */
    public function middleware(MezzoMiddleware $mezzoMiddleware)
    {
        $this->cockpitRouter()->laravelRouter()->middleware($mezzoMiddleware->key(), get_class($mezzoMiddleware));
    }


    /**
     * @param ModuleProvider $module
     */
    public function setModule($module)
    {
        $this->module = $module;

        $this->apiRouter->setModule($module);
        $this->cockpitRouter->setModule($module);
    }


}