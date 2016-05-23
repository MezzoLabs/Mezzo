<?php


namespace MezzoLabs\Mezzo\Core\Modularisation;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\Generic\AbstractGeneralModule;
use MezzoLabs\Mezzo\Core\Reflection\ReflectionManager;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;
use MezzoLabs\Mezzo\Core\Reflection\Reflectors\MezzoModelsReflector;
use MezzoLabs\Mezzo\Exceptions\MezzoException;
use MezzoLabs\Mezzo\Exceptions\ModelCannotBeAssociated;
use MezzoLabs\Mezzo\Exceptions\ModelCannotBeFound;
use MezzoLabs\Mezzo\Exceptions\ModelDoesntUseMezzoTrait;
use MezzoLabs\Mezzo\Exceptions\ModuleCenterException;
use MezzoLabs\Mezzo\Exceptions\ModuleNotFound;
use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Http\Pages\ModulePages;

class ModuleCenter
{
    /**
     * A collection of the registered Module providers
     *
     * @var Collection
     */
    protected $modules;

    /**
     * @var MezzoModelsReflector
     */
    protected $reflector;

    /**
     * @var Mezzo
     */
    private $mezzo;

    /**
     * @var ReflectionManager
     */
    private $reflectionManager;

    /**
     * @var Collection
     */
    private $slugAliases;

    /**
     * @param Mezzo $mezzo
     * @param ReflectionManager $reflectionManager
     */
    public function __construct(Mezzo $mezzo, ReflectionManager $reflectionManager)
    {
        $this->mezzo = $mezzo;
        $this->modules = new Collection();
        $this->slugAliases = new Collection();

        $this->registerGeneralModule($mezzo->make('mezzo.modules.general'));

        $this->reflectionManager = $reflectionManager;
        $this->reflector = $reflectionManager->mezzoModelReflector();
    }

    /**
     * Register the module that catches all the models without any module association
     *
     * @param AbstractGeneralModule $generalModule
     * @throws MezzoException
     * @internal param string $generalModuleClass
     */
    public function registerGeneralModule(AbstractGeneralModule $generalModule)
    {
        $this->register($generalModule, 'general');
    }

    /**
     * Add a new module to the Mezzo module center.
     *
     * @param String $moduleProviderClass
     * @param string $slug
     * @throws MezzoException
     * @return \MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider
     */
    public function register($moduleProviderClass, $slug = "")
    {
        $moduleProvider = false;

        if (is_object($moduleProviderClass)) {
            $moduleProvider = $moduleProviderClass;
            $moduleProviderClass = get_class($moduleProviderClass);
        }

        if ($this->isRegistered($moduleProviderClass))
            return false;

        if (!$moduleProvider)
            $moduleProvider = $this->makeModuleProvider($moduleProviderClass);

        if (empty($slug))
            $slug = $moduleProvider->slug();

        if ($this->slugAliases->has($slug))
            throw new MezzoException('Module with the slug ' . $slug . ' is already registered. ' .
                'Conflict between ' . $moduleProviderClass . ' and ' . $this->slugAliases->get($slug));

        $this->slugAliases->put($slug, get_class($moduleProvider));


        $this->mezzo->app()->register($moduleProvider);
        $this->mezzo->app()->instance(get_class($moduleProvider), $moduleProvider);

        $this->put($moduleProvider);

        event('mezzo.modules.registered: ' . $slug);

        return $moduleProvider;
    }

    /**
     * Checks if a module class is already registered
     *
     * @param string $moduleProviderClass
     * @return bool
     */
    public function isRegistered($moduleProviderClass)
    {
        return $this->modules->has($moduleProviderClass);
    }

    /**
     * @param $class
     * @throws \Exception
     * @return ModuleProvider
     */
    protected function makeModuleProvider($class)
    {
        $provider = $this->mezzo->make($class);

        if (!is_subclass_of($provider, ModuleProvider::class))
            throw new MezzoException('Given class is not a valid module provider. ' . $class);

        return $provider;
    }

    /**
     * Put function that only accepts module providers.
     *
     * @param ModuleProvider $moduleProvider
     */
    protected function put(ModuleProvider $moduleProvider)
    {
        $this->modules->put($moduleProvider->qualifiedName(), $moduleProvider);
    }

    /**
     * Check the models that each module requires and associate the models with them.
     * Make sure there are no conflicts.
     */
    public function associateModels()
    {
        $this->modules()->each(function (ModuleProvider $module) {

            if ($this->isGeneralModule($module)) return true;

            foreach ($module->modelClasses() as $modelClassName) {
                $this->associateModel($modelClassName, $module);
            }
        });

        $this->fillGeneralModule();

    }

    /**
     * @return Collection
     */
    public function modules()
    {
        return $this->modules;
    }

    /**
     * Check if the given instance is the general module.
     *
     * @param $instance
     * @return bool
     */
    public function isGeneralModule($instance)
    {
        $className = get_class($instance);
        $generalClassName = get_class($this->generalModule());

        return $className == $generalClassName;
    }

    /**
     * Get the general module
     *
     * @return AbstractGeneralModule
     */
    public function generalModule()
    {
        return $this->getModule('general');
    }

    /**
     * Get a module from the registration
     *
     * @param string $key
     * @return ModuleProvider
     */
    public function getModule($key)
    {
        $moduleClass = $this->moduleClass($key);

        return $this->modules->get($moduleClass);
    }

    protected function moduleClass($module)
    {
        if (is_object($module))
            $module = get_class($module);

        if (!is_string($module))
            throw new MezzoException('Cannot convert ' . gettype($module) . ' into a module class.');

        if ($this->slugAliases->has(camel_to_slug($module)))
            return $this->slugAliases->get(camel_to_slug($module));

        if ($this->modules()->has($module))
            return $module;

        throw new ModuleNotFound($module);
    }

    /**
     * Connect a model to this module. It will be blocked for other modules to grab this model afterwards.
     *
     * @param $model
     * @param ModuleProvider $module
     * @throws ModelCannotBeAssociated
     * @throws ModelDoesntUseMezzoTrait
     * @throws \MezzoLabs\Mezzo\Exceptions\ModelIsAlreadyAssociated
     * @internal param $className
     */
    public function associateModel($model, ModuleProvider $module)
    {
        $modelReflectionSet = $this->findModelReflectionSet($model);

        if (!$modelReflectionSet)
            throw new ModelCannotBeAssociated($model, $module);

        if (!$modelReflectionSet->isMezzoModel())
            throw new ModelDoesntUseMezzoTrait($model . " doesn't use the mezzo trait but is associated with a module.");

        $modelReflectionSet->mezzoReflection()->setModule($module);
    }

    /**
     * @param $model
     * @throws MezzoException
     * @throws ModelCannotBeFound
     * @return ModelReflectionSet
     */
    public function findModelReflectionSet($model)
    {
        if ($model instanceof ModelReflectionSet)
            return $model;

        return $this->reflector->modelReflectionSet($model);
    }

    /**
     * Grab the unassociated models and give them to the general module.
     */
    private function fillGeneralModule()
    {
        $reflectionSets = $this->reflector->modelReflectionSets();

        $reflectionSets->map(function (ModelReflectionSet $set, $key) {
            if ($set->mezzoReflection()->hasModule()) return;

            $this->associateWithGeneralModule($set);
        });
    }

    /**
     * @param ModelReflectionSet $model
     */
    public function associateWithGeneralModule(ModelReflectionSet $model)
    {
        $this->associateModel($model, $this->generalModule());
    }

    /**
     * @return Collection
     */
    public function groups()
    {
        $groups = ModuleGroup::groupsFromConfiguration();

        $this->modules()->each(function (ModuleProvider $module) use ($groups) {
            if (!$groups->has($module->groupName()))
                throw new ModuleCenterException('Module group ' . $module->groupName() . ' ' .
                    'is not defined in the configuration.');

            $groups->get($module->groupName())->addModule($module);
        });


        return $groups;
    }

    public function visibleGroups()
    {
        return $this->groups()->filter(function (ModuleGroup $group) {
            return $group->isVisible();
        });
    }

    /**
     * @param array $modulesExtensions
     */
    public function registerExtensions(array $modulesExtensions)
    {
        foreach ($modulesExtensions as $moduleSlug => $extensions) {
            $module = $this->getModule($moduleSlug);
            $module->registerExtension($extensions);
        }
    }

    protected function collectPages()
    {
        $pages = new ModulePages();

        $this->modules()->each(function (ModuleProvider $module) use (&$pages) {
            $pages = $pages->merge($module->pages());
        });

        return $pages;
    }

    public function pages($name = null)
    {
        if (empty($name)) {
            return $this->collectPages();
        }

        $found = $this->findPage($name);

        return $found;
    }

    protected function findPage($name)
    {
        return $this->collectPages()->search(function (ModulePage $item, $key) use ($name) {
            if (strtolower($key) == strtolower($name)) return true;

            if (strtolower(class_basename($item)) == strtolower($name)) return true;

            return false;
        });
    }

}