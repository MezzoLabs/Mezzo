<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use Illuminate\Foundation\Application;
use MezzoLabs\Mezzo\Console\MezzoKernel;
use MezzoLabs\Mezzo\Core\Annotations\Reader\AnnotationReader;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Configuration\MezzoConfig;
use MezzoLabs\Mezzo\Core\Database\Reader;
use MezzoLabs\Mezzo\Core\Files\FileTypeDetector;
use MezzoLabs\Mezzo\Core\Helpers\Path;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleCenter;
use MezzoLabs\Mezzo\Core\Modularisation\NamingConvention;
use MezzoLabs\Mezzo\Core\Reflection\ModelFinder;
use MezzoLabs\Mezzo\Core\Reflection\ModelLookup;
use MezzoLabs\Mezzo\Core\Reflection\ReflectionManager;
use MezzoLabs\Mezzo\Core\Routing\ApiConfig;
use MezzoLabs\Mezzo\Core\Routing\Uri;
use MezzoLabs\Mezzo\Core\ThirdParties\ThirdParties;
use MezzoLabs\Mezzo\Http\Requests\Request;
use MezzoLabs\Mezzo\Http\Transformers\TransformerRegistrar;
use MezzoLabs\Mezzo\Modules\General\GeneralModule;

class RegisterBindings implements Bootstrapper
{

    /**
     * Important singleton bindings.
     *
     * @var array
     */
    protected $instances = [
        'mezzo.thirdParties' => ThirdParties::class,
        'mezzo.config' => MezzoConfig::class,
        'mezzo.path' => Path::class,
        'mezzo.modelfinder' => ModelFinder::class,
        'mezzo.modelMappings' => ModelLookup::class,
        'mezzo.annotationReader' => AnnotationReader::class,
        'mezzo.reflectionManager' => ReflectionManager::class,
        'mezzo.modules.general' => GeneralModule::class,
        'mezzo.moduleCenter' => ModuleCenter::class,
        'mezzo.database.reader' => Reader::class,
        'mezzo.cache.singleton' => Singleton::class,
        'mezzo.kernel' => MezzoKernel::class
    ];

    protected $singletons = [
        ApiConfig::class,
        Uri::class,
        NamingConvention::class,
        TransformerRegistrar::class,
    ];


    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $app = $mezzo->app();

        $app->instance('mezzo', $mezzo);
        $app->alias('mezzo', get_class($mezzo));

        $this->bindInstances($mezzo);
        $this->bindSingletons($mezzo);
        $this->bindRequests($mezzo->app());
    }

    /**
     * Bind the configured instances
     *
     * @param Mezzo $mezzo
     */
    protected function bindInstances(Mezzo $mezzo)
    {
        foreach ($this->instances as $key => $class) {
            $this->bindInstance($mezzo->app(), $key, $class);
        }
    }

    /**
     * Bind a key and the full class name to a single instance.
     *
     * @param Application $app
     * @param $key
     * @param $class
     */
    protected function bindInstance(Application $app, $key, $class)
    {
        $instance = $app->make($class);

        $app->instance($key, $instance);
        $app->alias($key, $class);

    }

    /**
     * @param $mezzo
     */
    private function bindSingletons(Mezzo $mezzo)
    {
        foreach ($this->singletons as $class) {
            $this->bindSingleton($mezzo->app(), $class);
        }
    }

    /**
     * @param Application $app
     * @param $class
     */
    private function bindSingleton(Application $app, $class)
    {
        $app->singleton($class, $class);
    }

    private function bindRequests(Application $app)
    {
        $app->singleton(Request::class, function (Application $app) {
            return Request::capture();
        });
    }


}