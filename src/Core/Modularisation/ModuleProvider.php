<?php


namespace MezzoLabs\Mezzo\Core\Modularisation;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\AttributeRenderEngine;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\Extensions\ExtensibleModule;
use MezzoLabs\Mezzo\Core\Modularisation\Extensions\HasModuleExtensions;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflections;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSets;
use MezzoLabs\Mezzo\Core\Routing\ApiExceptionHandler;
use MezzoLabs\Mezzo\Core\Routing\ModuleRouter;
use MezzoLabs\Mezzo\Exceptions\DirectoryNotFound;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Controllers\ApiResourceController;
use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Controllers\Controller;
use MezzoLabs\Mezzo\Http\Controllers\GenericApiResourceController;
use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Http\Pages\ModulePages;
use MezzoLabs\Mezzo\Http\Transformers\TransformerRegistrar;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\ContentBlockTypeRegistrar;
use MezzoLabs\Mezzo\Modules\General\Options\OptionFieldRegistry;

abstract class ModuleProvider extends ServiceProvider implements ExtensibleModule
{
    use HasModuleExtensions;

    /**
     * @var String[]
     */
    protected $models = [];

    /**
     * These MezzoCommands will be registered for the artisan command line.
     *
     * @var array
     */
    protected $commands = [];


    /**
     * @var ModelReflectionSets
     */
    protected $modelReflectionSets;

    /**
     * @var Mezzo
     */
    protected $mezzo;

    /**
     * @var ModuleRouter
     */
    protected $router;

    /**
     * @var ModulePages
     */
    protected $pages;

    /**
     * @var string
     */
    protected $group = 'general';

    /**
     * Options that will determine the style of this module.
     *
     * @var array|Collection
     */
    protected $options = [
    ];

    /**
     * Will be overwritten by the $options array.
     *
     * @var array
     */
    private $defaultOptions = [
        'icon' => 'ion-ios-copy',
        'visible' => true,
        'title' => null,
        'permissions' => ''
    ];


    /**
     * Create a new module provider instance
     *
     * @param Application $app
     * @internal param Application $app
     * @internal param Mezzo $mezzo
     */
    public function __construct(Application $app)
    {
        $this->mezzo = mezzo();;
        $this->modelReflectionSets = new ModelReflectionSets();

        $this->router = new ModuleRouter($this);

        $this->app = $app;
        $this->defaultOptions = new Collection($this->defaultOptions);
        $this->options = $this->defaultOptions->merge($this->options);
    }

    /**
     * Factory method for creating a Mezzo module instance.
     *
     * @return static
     */
    public static function make()
    {
        return mezzo()->module(static::class);
    }

    /**
     * Called when module is ready, model reflections are loaded.
     *
     * @return mixed
     */
    abstract public function ready();

    /**
     * @return \String[]
     */
    public function modelClasses()
    {
        return $this->models;
    }

    /**
     * @param string $key
     * @return MezzoModelReflection
     */
    public function model($key)
    {
        return $this->reflectionSet($key)->mezzoReflection();
    }

    /**
     * @param $key
     * @return ModelReflectionSet
     */
    public function reflectionSet($key)
    {
        return $this->modelReflectionSets->getReflectionSet($key);
    }

    /**
     * @return ModelReflections
     */
    public function models()
    {
        return $this->reflectionSets()->mezzoReflections();
    }

    /**
     * The reflections of the associated models
     *
     * @internal param bool $key
     * @return ModelReflectionSets
     */
    public function reflectionSets()
    {
        return $this->modelReflectionSets;
    }

    /**
     * @param ModelReflection|ModelReflectionSet $modelReflectionSet
     * @throws \MezzoLabs\Mezzo\Exceptions\ReflectionException
     */
    public function associateModel(ModelReflectionSet $modelReflectionSet)
    {
        $this->modelReflectionSets->addReflectionSet($modelReflectionSet);
    }

    /**
     * @param $shortAbstract
     * @param $concrete
     */
    public function bindWithPrefix($shortAbstract, $concrete, $singleton = false)
    {
        $abstract = 'modules.' . $this->slug() . '.' . $shortAbstract;

        $this->app->bind($concrete, $concrete, $singleton);
        $this->app->alias($concrete, $abstract);

        if ($singleton) {
            $this->app->singleton($abstract, $concrete);
        }

    }

    /**
     * Returns the key of the module based on the ModuleProvider class name in "slug case".
     *
     * Example: MySampleModule -> my-sample
     *
     * @return string
     */
    public function slug()
    {
        $class = $this->reflection()->getShortName();
        $class = preg_replace('/Module/', '', $class);

        return camel_to_slug($class);
    }

    /**
     * @return \ReflectionClass
     */
    public function reflection()
    {
        return Singleton::reflection(get_class($this));
    }

    /**
     * @param $shortAbstract
     * @return mixed
     */
    public function makeWithPrefix($shortAbstract)
    {
        return $this->app->make('modules.' . $this->slug() . '.' . $shortAbstract);
    }

    /**
     * @internal param MezzoKernel $kernel
     */
    public function loadCommands()
    {
        $this->mezzo->kernel()->registerCommands($this->commands);
    }

    /**
     * @throws ModuleControllerException
     */
    public function includeRoutes()
    {
        $this->router()->includeRoutesFile();
    }

    /**
     * @return ModuleRouter
     */
    public function router()
    {
        return $this->router;
    }

    /**
     * @param $name
     * @return ModulePage
     * @throws InvalidArgumentException
     * @throws \MezzoLabs\Mezzo\Exceptions\NamingConventionException
     */
    public function makePage($name)
    {
        if (is_object($name)) {
            if ($name instanceof ModulePage) return $name;

            throw new InvalidArgumentException($name);
        };

        $pageClass = NamingConvention::findPageClass($this, $name);

        return new $pageClass($this);
    }

    /**
     * @return string
     */
    public function getNamespaceName()
    {
        return Singleton::reflection($this)->getNamespaceName();
    }

    /**
     * Returns the unique identifier of this module.
     *
     * @return string
     */
    public function qualifiedName()
    {
        return get_class($this);
    }

    /**
     * @return ModulePages
     */
    public function pages()
    {
        if (!$this->pages)
            $this->pages = $this->collectPages();

        return $this->pages;
    }

    public function hasVisiblePages()
    {
        return $this->pages()->filterVisibleInNavigation()->count() > 0;
    }

    protected function collectPages()
    {
        $pages = new ModulePages();
        $pages->collectFromModule($this);
        return $pages;
    }

    /**
     * @return string
     */
    public function groupName()
    {
        if (empty($this->group))
            return 'general';

        return $this->group;
    }

    /**
     * @param null $key
     * @param null $default
     * @return array|Collection
     */
    public function options($key = null, $default = null)
    {
        if ($key)
            return $this->options->get($key, $default);

        return $this->options;
    }

    /**
     * Get the api resource controller with the ControllerName
     *
     * @param $controllerName
     * @return GenericApiResourceController|CockpitResourceController
     * @throws ModuleControllerException
     */
    public function apiResourceController($controllerName)
    {
        $controller = $this->resourceController($controllerName);

        if (!($controller instanceof ApiResourceController))
            throw new ModuleControllerException($controller->qualifiedName() . ' is not an API resource controller. ');

        return $controller;
    }

    /**
     * @param $controllerName
     * @return ResourceController
     * @throws InvalidArgumentException
     * @throws ModuleControllerException
     */
    public function resourceController($controllerName)
    {
        $controller = $this->makeController($controllerName);

        if (!$controller->isResourceController()) {
            throw new ModuleControllerException($controller->qualifiedName() . ' is not a valid resource controller.');

        }

        return $controller;
    }

    /**
     * Create a new controller instance.
     *
     * @param $controllerName
     * @return Controller
     * @throws InvalidArgumentException
     * @throws ModuleControllerException
     */
    public function makeController($controllerName)
    {
        if (is_object($controllerName)) {
            if ($controllerName instanceof Controller) return $controllerName;

            throw new InvalidArgumentException($controllerName);
        }

        $controller = mezzo()->make($this->controllerClass($controllerName));

        if (!($controller instanceof Controller))
            throw new ModuleControllerException('Not a valid module controller.');

        return $controller;
    }

    /**
     * Find the full class name for a controller.
     *
     * @param $controllerName
     * @return string
     * @throws ModuleControllerException
     */
    public function controllerClass($controllerName)
    {
        return NamingConvention::controllerClass($this, $controllerName);
    }

    public function generateRoutes()
    {
        $this->router()->generateRoutes();
    }

    /**
     * @return mixed
     */
    public function title()
    {
        if (Lang::has('mezzo.modules.' . strtolower($this->shortName()))) {
            $title = Lang::get('mezzo.modules.' . strtolower($this->shortName()));

            return (!is_array($title)) ? $title : $title['title'];

        }

        if ($this->options->get('title'))
            return $this->options->get('title');


        return $this->shortName();
    }

    /**
     * @return mixed
     */
    public function shortName()
    {
        $classShortName = Singleton::reflection($this)->getShortName();

        return str_replace('Module', '', $classShortName);
    }

    /**
     * @return string
     */
    public function uri()
    {
        return mezzo()->uri()->toModule($this);
    }

    /**
     * Called when all service pro
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Load views from the "views" folder inside the module root.
     * The namespace will be modules.<modulename>::<view_name>
     *
     * @throws DirectoryNotFound
     */
    protected function loadViews()
    {
        if (!is_dir($this->path() . '/Resources/Views'))
            throw new DirectoryNotFound($this->path() . '/Resources/Views');

        $this->loadViewsFrom($this->path() . '/Resources/Views', 'modules.' . $this->slug());
    }

    /**
     * Path to the module folder
     *
     * @return string
     */
    public function path()
    {
        $fileName = $this->reflection()->getFileName();
        return dirname($fileName);
    }

    /**
     * Register the eloquent model transformers.
     */
    protected function registerTransformers($array = [])
    {
        app(TransformerRegistrar::class)->addTransformers($array);

    }

    /**
     * Register a Exception and use your own callback to handle it.
     *
     * @param callable $callback
     */
    protected function registerApiException(callable $callback)
    {
        app(ApiExceptionHandler::class)->register($callback);
    }

    /**
     * Register a group of content blocks that will be in the selection of the content builder.
     *
     * @param array $contentBlocks
     */
    protected function registerContentBlocks(array $contentBlocks)
    {
        ContentBlockTypeRegistrar::register($contentBlocks);
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        if (!$this->isAllowed())
            return false;

        if (!$this->hasVisiblePages())
            return false;

        return $this->options()->get('visible', true);
    }

    public function isAllowed()
    {
        return Auth::user()->hasPermissions($this->permissions());
    }

    public function permissions() : array
    {
        if (empty($this->options('permissions')))
            return [];

        if (is_array($this->options('permissions'))) {
            return $this->options('permissions');
        }

        return explode('|', $this->options('permissions'));
    }

    public function registerAttributeRenderer($rendererClasses = [])
    {
        if (!is_array($rendererClasses))
            $rendererClasses = [$rendererClasses];

        foreach ($rendererClasses as $rendererClass) {
            AttributeRenderEngine::registerHandler($rendererClass);
        }
    }

    /**
     * @return OptionFieldRegistry
     */
    public function optionRegistry()
    {
        return app()->make('mezzo.options.registry');
    }

}