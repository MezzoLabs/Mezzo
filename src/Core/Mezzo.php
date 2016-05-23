<?php


namespace MezzoLabs\Mezzo\Core;


use Illuminate\Foundation\Application;
use MezzoLabs\Mezzo\Core\Booting\BootManager;
use MezzoLabs\Mezzo\Core\Logging\Logger as MezzoLogger;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Traits\CanFireEvents;
use MezzoLabs\Mezzo\Core\Traits\CanMakeInstances;
use MezzoLabs\Mezzo\Events\Core\MezzoBooted;
use MezzoLabs\Mezzo\Exceptions\ReflectionException;
use MezzoLabs\Mezzo\MezzoServiceProvider;
use MezzoLabs\Mezzo\Modules\General\Options\OptionsService;

class Mezzo
{

    use CanMakeInstances, CanFireEvents;

    /**
     * The mezzo service provider that starts all this stuff.
     *
     * @var MezzoServiceProvider
     */
    public $serviceProvider;
    /**
     * Indicates if mezzo has "booted".
     *
     * @var bool
     */
    protected $booted = false;
    /**
     * The Laravel Application
     *
     * @var Application
     */
    protected $app;
    /**
     * The core boot service that runs all the bootstrappers we need.
     *
     * @var BootManager
     */
    protected $bootManager;

    /**
     * Create the one and only Mezzo instance
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->bootManager = BootManager::make($this);
    }

    /**
     * Get the Laravel application
     *
     * @return Application
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Get a module instance by key(slug or class name)
     *
     * @param $key
     * @return \MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider
     */
    public function module($key)
    {
        return $this->moduleCenter()->getModule($key);
    }

    /**
     * @param $modelName
     * @param string $reflectionType
     * @return Reflection\Reflections\ModelReflection|MezzoModelReflection|EloquentModelReflection
     */
    public function model($modelName, $reflectionType = "best")
    {
        $reflectionManager = $this->makeReflectionManager();

        if ($reflectionType == "mezzo")
            return $reflectionManager->mezzoReflection($modelName);

        if ($reflectionType == "eloquent")
            return $reflectionManager->eloquentReflection($modelName);

        return $reflectionManager->modelReflection($modelName);

    }

    /**
     * Checks if there if a model is registered in the reflector.
     *
     * @param $modelName
     * @return bool
     */
    public function knowsModel($modelName)
    {
        return $this->makeReflectionManager()->modelIsReflected($modelName);
    }

    /**
     * @param $modelName
     * @param $attributeName
     * @param bool $forceEloquent
     * @return Attribute|RelationAttribute
     * @throws ReflectionException
     */
    public function attribute($modelName, $attributeName, $forceEloquent = false)
    {
        if (!$forceEloquent) {
            $bestModel = $this->model($modelName, 'best');

            if ($bestModel && $bestModel->isMezzoModel() && $bestModel->attributes()->has($attributeName))
                return $bestModel->attributes()->get($attributeName);
        }

        $eloquentModel = $this->model($modelName, 'eloquent');
        if ($eloquentModel && $eloquentModel->attributes()->has($attributeName))
            return $eloquentModel->attributes()->get($attributeName);

        throw new ReflectionException('Cannot find attribute "' . $attributeName . '" in "' . $modelName . '".');
    }

    /**
     * Run the boot services that we need at the time the Mezzo provider is registered
     */
    public function onProviderRegistered()
    {
        $this->bootManager->runRegisterPhase();
    }

    /**
     * Run the boot services that we need at the time the when all service providers are registered
     */
    public function onProviderBooted()
    {
        $this->bootManager->runBootPhase();
    }

    /**
     * Run the boot services that we need at the time the when all service providers are booted
     */
    public function onAllProvidersBooted()
    {
        if ($this->booted) return false;

        $this->bootManager->bootedPhase();

        $this->booted = true;

        $this->fire(MezzoBooted::class);

    }

    /**
     * Get a value from the mezzo config.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->makeConfiguration()->get($key, $default);
    }

    /**
     * @param null $name
     * @param null $value
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws \MezzoLabs\Mezzo\Modules\General\Exceptions\OptionNotFoundException
     */
    public function option($name = null, $value = null)
    {
        $optionsService = app()->make(OptionsService::class);

        if ($name === null)
            return $optionsService->collection()->pluck('value', 'name');

        if ($value === null)
            return $optionsService->get($name);

        return $optionsService->set($name, $value);

    }

    /**
     * @return Logging\Logger
     */
    public function logger()
    {
        return MezzoLogger::make();
    }


}