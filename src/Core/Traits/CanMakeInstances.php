<?php
/**
 * Created by: simon.schneider
 * Date: 17.09.2015 - 15:20
 * Project: MezzoDemo
 */


namespace MezzoLabs\Mezzo\Core\Traits;


use Illuminate\Contracts\Http\Kernel as LaravelHttpKernel;
use MezzoLabs\Mezzo\Cockpit\CockpitProvider;
use MezzoLabs\Mezzo\Console\MezzoKernel;
use MezzoLabs\Mezzo\Core\Annotations\Reader\AnnotationReader;
use MezzoLabs\Mezzo\Core\Configuration\MezzoConfig;
use MezzoLabs\Mezzo\Core\Database\Reader;
use MezzoLabs\Mezzo\Core\Helpers\Path;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleCenter;
use MezzoLabs\Mezzo\Core\Reflection\ModelFinder;
use MezzoLabs\Mezzo\Core\Reflection\ModelLookup;
use MezzoLabs\Mezzo\Core\Reflection\ReflectionManager;
use MezzoLabs\Mezzo\Core\Reflection\Reflectors\MezzoModelsReflector;
use MezzoLabs\Mezzo\Core\Routing\Router;
use MezzoLabs\Mezzo\Core\Routing\Uri;
use MezzoLabs\Mezzo\Core\ThirdParties\ThirdParties;
use MezzoLabs\Mezzo\Exceptions\MezzoException;
use MezzoLabs\Mezzo\Exceptions\UnexpectedException;
use MezzoLabs\Mezzo\Http\Requests\Request;
use MezzoLabs\Mezzo\Modules\FileManager\FileManagerModule;

trait CanMakeInstances
{
    /**
     * A quick access for the Laravel IoC Container
     *
     * @param $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, $parameters = [])
    {
        return app()->make($abstract, $parameters);
    }

    /**
     * Returns the main Module Center instance.
     *
     * @return ModuleCenter
     */
    public function moduleCenter()
    {
        return $this->make(ModuleCenter::class);
    }

    /**
     * Returns the main Mezzo Console Kernel instance
     *
     * @return MezzoKernel
     */
    public function kernel()
    {
        return $this->make(MezzoKernel::class);
    }

    /**
     * Return the mezzo models reflector instance.
     *
     * @return MezzoModelsReflector
     */
    public function reflector()
    {
        return $this->makeReflectionManager()->mezzoModelReflector();
    }

    /**
     * @return ReflectionManager
     */
    public function makeReflectionManager()
    {
        return $this->make(ReflectionManager::class);
    }

    /**
     * @return ModelLookup
     */
    public function makeModelLookup()
    {
        return $this->make(ModelLookup::class);
    }

    /**
     * @return AnnotationReader
     */
    public function makeAnnotationReader()
    {
        return $this->make(AnnotationReader::class);
    }

    /**
     * @return FileManagerModule
     */
    public function makeFileManagerModule()
    {
        return mezzo()->module('FileManager');
    }

    /**
     * Return the model reflector singleton instance.
     *
     * @return Reader
     */
    public function makeDatabaseReader()
    {
        return $this->make(Reader::class);
    }

    /**
     * Return the model finder singleton instance.
     *
     * @return ModelFinder
     */
    public function makeModelFinder()
    {
        return $this->make(ModelFinder::class);
    }

    /**
     * Return the mezzo router singleton instance.
     *
     * @return Router
     */
    public function makeRouter()
    {
        return $this->make(Router::class);
    }

    /**
     * Returns the main MezzoConfig instance
     *
     * @return MezzoConfig
     */
    public function makeConfiguration()
    {
        return $this->make(MezzoConfig::class);
    }

    /**
     * Gives you access to the Path helper singleton
     *
     * @return Path
     */
    public function path()
    {
        return app()->make('mezzo.path');
    }

    /**
     * Returns an instance of the illuminate view factory.
     *
     * @return \Illuminate\View\Factory
     */
    public function makeViewFactory()
    {
        return app(\Illuminate\Contracts\View\Factory::class);
    }

    /**
     * @return ThirdParties
     */
    public function makeThirdParties()
    {
        return $this->make(ThirdParties::class);
    }

    /**
     * @return \MezzoLabs\Mezzo\Cockpit\Cockpit
     * @throws UnexpectedException
     */
    public function makeCockpit()
    {
        $cockpitProvider = app()->getProvider(CockpitProvider::class);

        if(!$cockpitProvider instanceof CockpitProvider)
            throw new MezzoException("The cockpit provider is not ready yet. " .
                "Please set your call in the \"ready\" block of your service provider.");

        return $cockpitProvider->cockpit();
    }

    /**
     * @return LaravelHttpKernel
     */
    public function makeLaravelHttpKernel()
    {
        return $this->make(LaravelHttpKernel::class);
    }

    /**
     * @return Request
     */
    public function makeRequest()
    {
        return Request::capture();
    }

    /**
     * @return Uri
     */
    public function uri()
    {
        return $this->make(Uri::class);
    }
} 