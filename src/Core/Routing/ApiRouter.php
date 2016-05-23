<?php


namespace MezzoLabs\Mezzo\Core\Routing;

use Closure;
use Dingo\Api\Routing\Router as DingoRouter;
use MezzoLabs\Mezzo\Core\ThirdParties\Wrappers\DingoApi;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;

class ApiRouter
{
    use CanHaveModule, CanHaveGroupedRouter;

    /**
     * @var ApiConfig
     */
    protected $config;

    /**
     * @var DingoRouter
     */
    private $dingoRouter;

    /**
     * @throws \MezzoLabs\Mezzo\Exceptions\RoutingException
     */
    public function __construct()
    {
        $this->dingoRouter = DingoApi::make()->getDingoRouter();
        $this->config = $this->makeApiConfig();
    }

    /**
     * @return ApiConfig
     */
    protected static function makeApiConfig()
    {
        return mezzo()->make(ApiConfig::class);
    }

    /**
     * @param $uri
     * @param $action
     * @return mixed
     */
    public function patch($uri, $action)
    {
        return $this->dingoRouter()->patch($uri, $action);
    }

    /**
     * @return DingoRouter
     */
    public function dingoRouter()
    {
        if ($this->hasGroupedRouter())
            return $this->groupedRouter;

        return $this->dingoRouter;
    }


    /**
     * @param array $overwriteAttributes
     * @param callable|Closure $callback
     */
    public function group(array $overwriteAttributes, Closure $callback = null)
    {
        $attributes = $this->config->merge($overwriteAttributes)->toArray();

        $this->dingoRouter()->group($attributes, function (DingoRouter $router) use ($callback) {
            $this->setGroupedRouter($router);

            if ($callback !== null)
                call_user_func($callback, $this);
        });
    }


    public function moduleAction($controllerAction)
    {
        $this->hasModuleOrFail();

        if (!is_string($controllerAction) || strpos($controllerAction, '@') == -1)
            throw new InvalidArgumentException($controllerAction);

        $parts = explode('@', $controllerAction);

        $controller = $this->module->makeController($parts[0]);
        $method = $parts[1];

        $uri = mezzo()->uri()->toModuleAction($this->module, $controller, $method);

        return $this->get($uri, $controller->qualifiedActionName($method));
    }

    /**
     * @param string $uri
     * @param array|string|callable $action
     * @return mixed
     */
    public function get($uri, $action)
    {
        return $this->dingoRouter()->get($uri, $action);
    }

    /**
     * Creates the restful routes for a certain resource controller.
     *
     * @param $modelName
     * @param string $controllerName
     * @throws ModuleControllerException
     */
    public function relation(string $modelName, string $relationName, string $controllerName)
    {
        $controller = $this->module->apiResourceController($controllerName);

        $uri = $this->modelUri($controller->model());

        $this->get($uri . '/{id}/' . camel_to_slug($relationName), [
            'uses' => $controller->qualifiedActionName('index'),
            'as' => 'api::' . snake_case($modelName) . '.'. snake_case($relationName) . '.index'
        ]);
    }


    /**
     * Creates the restful routes for a certain resource controller.
     *
     * @param $modelName
     * @param string $controllerName
     * @throws ModuleControllerException
     */
    public function resource($modelName, $controllerName = "")
    {
        if (empty($controllerName))
            $controllerName = $this->controllerName($modelName);

        $controller = $this->module->apiResourceController($controllerName);

        $uri = $this->modelUri($controller->model());

        $this->get($uri, [
            'uses' => $controller->qualifiedActionName('index'),
            'as' => 'api::' . snake_case($modelName) . '.index'
        ]);

        $this->get($uri . '/info', [
            'uses' => $controller->qualifiedActionName('info'),
            'as' => 'api::' . snake_case($modelName) . '.info'
        ]);

        $this->get($uri . '/{id}', [
            'uses' => $controller->qualifiedActionName('show'),
            'as' => 'api::' . snake_case($modelName) . '.show'
        ]);

        $this->post($uri, [
            'uses' => $controller->qualifiedActionName('store'),
            'as' => 'api::' . snake_case($modelName) . '.store'
        ]);

        $this->put($uri . '/{id}', [
            'uses' => $controller->qualifiedActionName('update'),
            'as' => 'api::' . snake_case($modelName) . '.update'
        ]);

        $this->delete($uri . '/{id}', [
            'uses' => $controller->qualifiedActionName('destroy'),
            'as' => 'api::' . snake_case($modelName) . '.destroy'
        ]);
    }

    public function modelUri($model)
    {
        $model = mezzo()->model($model);
        return camel_to_slug(str_plural($model->name()));
    }

    /**
     * @param string $uri
     * @param array|string|callable $action
     * @return mixed
     */
    public function post($uri, $action)
    {
        return $this->dingoRouter()->post($uri, $action);
    }

    /**
     * @param $uri
     * @param $action
     * @return mixed
     */
    public function put($uri, $action)
    {
        return $this->dingoRouter()->put($uri, $action);
    }

    /**
     * @param $uri
     * @param $action
     * @return mixed
     */
    public function delete($uri, $action)
    {
        return $this->dingoRouter()->delete($uri, $action);
    }



    protected function controllerName($modelName)
    {
        return $modelName . 'ApiController';
    }

    /**
     * Add a GET action to the routes.
     *
     * @param string $name
     * @param string $modelName
     * @param array $options
     * @throws ModuleControllerException
     */
    public function action(string $name, string $modelName, array $options)
    {
        $controllerName = $options['controller'] ?? $this->controllerName($modelName);
        $mode = $options['mode'] ?? 'single';

        $controller = $this->module->apiResourceController($controllerName);

        $uri = $this->modelUri($modelName);

        if ($mode == "single") {
            $uri .= '/{id}';
        }

        $uri .= '/' . $name;

        $this->get($uri, [
            'uses' => $controller->qualifiedActionName($name),
            'as' => 'api::' . snake_case($modelName) . '.' . snake_case($name)
        ]);
    }

}