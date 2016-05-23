<?php


namespace MezzoLabs\Mezzo\Core\Routing;

use Closure;
use Illuminate\Routing\Router as LaravelRouter;
use Illuminate\Support\Collection;


class CockpitRouter
{
    use CanHaveModule, CanHaveGroupedRouter;

    /**
     * @var LaravelRouter
     */
    protected $laravelRouter;

    /**
     * @var Collection
     */
    protected $attributes;


    /**
     * @param LaravelRouter $laravelRouter
     */
    public function __construct(LaravelRouter $laravelRouter)
    {
        $this->laravelRouter = $laravelRouter;

        $this->readConfig();
    }

    /**
     * Read the mezzo.cockpit configuration.
     */
    private function readConfig()
    {
        $cockpitConfig = mezzo()->config('cockpit');

        $this->attributes = new Collection([
            'prefix' => $cockpitConfig['prefix'],
            'as' => $cockpitConfig['namedRouteNamespace'],
        ]);

        if ($this->hasModule())
            $this->setDefaultControllerNamespace();
    }

    private function setDefaultControllerNamespace()
    {
        $namespace = $this->module->getNamespaceName() . '\Http';
        $this->attributes->put('namespace', $namespace);
    }

    /**
     * @param $overwriteAttributes
     * @param Closure $callback
     */
    public function group($overwriteAttributes, Closure $callback = null)
    {
        $this->readConfig();

        $attributes = $this->attributes->merge($overwriteAttributes);

        $this->laravelRouter()->group($attributes->toArray(), function (LaravelRouter $router) use ($callback) {
            $this->setGroupedRouter($router);

            if ($callback !== null)
                call_user_func($callback, $this);
        });
    }

    /**
     * @return LaravelRouter
     */
    public function laravelRouter()
    {
        if ($this->hasGroupedRouter())
            return $this->groupedRouter;

        return $this->laravelRouter;
    }

    /**
     * @param $modelName
     * @param array $pageTypes
     */
    public function resourcePages($modelName, $pageTypes = ['create', 'edit', 'index', 'show'])
    {
        foreach ($pageTypes as $pageType) {
            $pageName = ucfirst($pageType) . ucfirst($modelName) . 'Page';
            $this->page($pageName);
        }
    }

    /**
     * @param $pageName
     * @param bool $needsId
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     * @throws \MezzoLabs\Mezzo\Exceptions\UnexpectedException
     */
    public function page($pageName, $needsId = false)
    {
        $page = $this->module->makePage($pageName);

        $pageUri = mezzo()->uri()->toModulePage($page);
        $action = $this->shortenAction($page->qualifiedActionName());

        $cockpitAction = ($page->isRenderedByFrontend()) ? mezzo()->makeCockpit()->startAction() : $action;

        $this->get($pageUri . $page->options('appendToUri'),
            ['uses' => $cockpitAction, 'as' => $page->slug(), 'where' => ['id' => '[0-9]+']]
        );

        /*
         * Send the PAGE_ACTION.html request through the controller action.
         */
        $this->get(str_replace('/{id}', '', $pageUri) . '.html',
            ['uses' => $action, 'as' => $page->slug() . '_html']
        );
    }

    /**
     * @param $action
     * @return mixed
     */
    protected function shortenAction($action)
    {
        $namespace = $this->controllerNamespace();

        $shortAction = str_replace($namespace . '\\', '', $action);

        if($action == $shortAction)
            return $action;

        return ltrim($shortAction, '\\');
    }

    /**
     * @return mixed
     */
    public function controllerNamespace()
    {
        return $this->lastGroupStack()->get('namespace', '');
    }

    /**
     * @return Collection
     */
    public function lastGroupStack()
    {
        if (!empty($this->laravelRouter()->getGroupStack())) {
            $groupStack = $this->laravelRouter()->getGroupStack();
            return new Collection(end($groupStack));
        }

        return new Collection();
    }

    /**
     * @param $uri
     * @param $action
     * @return \Illuminate\Routing\Route
     */
    public function get($uri, $action)
    {
        return $this->laravelRouter()->get($uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    public function post($uri, $action)
    {
        return $this->laravelRouter()->post($uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    public function put($uri, $action)
    {
        return $this->laravelRouter()->put($uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    public function patch($uri, $action)
    {
        return $this->laravelRouter()->patch($uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string $uri
     * @param  \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    public function delete($uri, $action)
    {
        return $this->laravelRouter()->delete($uri, $action);
    }



}