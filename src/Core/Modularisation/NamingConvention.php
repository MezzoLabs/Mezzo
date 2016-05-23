<?php


namespace MezzoLabs\Mezzo\Core\Modularisation;


use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\NamingConventionException;
use MezzoLabs\Mezzo\Http\Controllers\ResourceControllerContract;
use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;
use MezzoLabs\Mezzo\Http\Transformers\GenericEloquentModelTransformer;

class NamingConvention
{
    /**
     * @param $object
     * @return ModuleProvider
     * @throws NamingConventionException
     */
    public static function findModule($object)
    {
        if (is_object($object) || is_string($object))
            return static::findModuleOfClass($object);

        throw new NamingConventionException('Cannot find module for ' . get_class($object));
    }

    /**
     * @param $className
     * @return null
     * @throws InvalidArgumentException
     * @throws NamingConventionException
     */
    public static function findModuleOfClass($className)
    {
        if (is_object($className))
            $className = get_class($className);

        if (!is_string($className))
            throw new InvalidArgumentException($className);

        $modules = mezzo()->moduleCenter()->modules();

        $found = null;

        $modules->each(function (ModuleProvider $module) use ($className, &$found) {
            $namespace = $module->getNamespaceName();

            if (strpos($className, $namespace) !== false) {
                $found = $module;
                return false;
            }
        });

        if (!$found)
            throw new NamingConventionException('The class "' . $className . '" is not inside a registered module.');

        return $found;
    }


    /**
     * Try to find the model that is connected via the naming.
     * <ModelName>Controller
     * <ModelName>ApiController
     * <ModelName>Repository
     * ...
     *
     * @param $object
     * @return mixed
     * @throws NamingConventionException
     */
    public static function modelName($object)
    {
        if ($object instanceof ResourceControllerContract)
            return static::modelNameForModuleController($object);

        if ($object instanceof EloquentModelTransformer)
            return static::modelNameForTransformer($object);

        throw new NamingConventionException('Cannot find model name for ' . get_class($object));
    }

    /**
     * Try to find a concrete repository implementation for a model class.
     *
     * @param $modelName
     * @param array $namespaces
     * @return bool|string
     */
    public static function repositoryClass($modelName, $namespaces = ['App'])
    {
        $modelName = str_replace(['App', '\\'], '', $modelName);

        foreach ($namespaces as $namespace) {
            $possibleRepository = $namespace . '\Domain\Repositories\\' . $modelName . 'Repository';

            if (class_exists($possibleRepository))
                return $possibleRepository;
        }

        return false;
    }

    /**
     * @param ResourceControllerContract $object
     * @return mixed
     */
    private static function modelNameForModuleController(ResourceControllerContract $object)
    {
        $shortName = Singleton::reflection($object)->getShortName();

        $possibleModelName = str_replace(['ApiController', 'Controller'], '', $shortName);

        return $possibleModelName;
    }

    private static function modelNameForTransformer($object)
    {
        if ($object instanceof GenericEloquentModelTransformer)
            throw new NamingConventionException('Cannot find model for a generic model transformer.');

        $shortName = Singleton::reflection($object)->getShortName();

        $possibleModelName = str_replace(['Transformer'], '', $shortName);

        return $possibleModelName;
    }

    /**
     * Get the full controller class via the module and the name of the controller.
     *
     * @param ModuleProvider $module
     * @param $controllerName
     * @return string
     * @throws NamingConventionException
     */
    public static function controllerClass(ModuleProvider $module, $controllerName)
    {

        if (is_object($controllerName))
            $controllerName = get_class($controllerName);

        if (!strpos($controllerName, 'Controller'))
            $controllerName .= 'Controller';

        if (class_exists($controllerName))
            return $controllerName;

        /**
         * Get the correct namespace depending on the type of the controller.
         */
        $controllerType = static::controllerType($controllerName);
        if ($controllerType == 'api')
            $controllerNamespace = $module->getNamespaceName() . '\\Http\\ApiControllers\\';
        else
            $controllerNamespace = $module->getNamespaceName() . '\\Http\\Controllers\\';

        /**
         * Check if controllerName exists and if it is inside the correct namespace
         */
        if (class_exists($controllerName) && strpos($controllerName, $controllerNamespace) !== -1)
            return $controllerName;

        /**
         * Check if controllerName is just the class name of the controller and prepend the correct namespace.
         */
        $longControllerName = $controllerNamespace . $controllerName;

        if (class_exists($longControllerName))
            return $longControllerName;

        throw new NamingConventionException('Module controller "' . $longControllerName . '"' .
            ' not found for "' . $module->qualifiedName() . '".');
    }

    /**
     * Determine the type of the controller based on his short class name.
     *
     * @param $controllerName
     * @return string
     */
    public static function controllerType($controllerName)
    {
        if (is_object($controllerName)) {
            $controllerName = get_class($controllerName);
        }

        $nameParts = explode('\\', $controllerName);
        $controllerName = $nameParts[count($nameParts) - 1];

        $isApi = ends_with($controllerName, 'ApiController');

        return ($isApi) ? 'api' : 'html';
    }

    public static function findPageClass(ModuleProvider $module, $name)
    {
        if (!is_string($name))
            throw new InvalidArgumentException($name);

        if (class_exists($name) && is_subclass_of($name, ModulePage::class))
            return $name;

        $possibleClass = static::pageNameSpace($module) . '\\' . $name;

        if (!class_exists($possibleClass))
            throw new NamingConventionException('No page found with the name ' . $name . ' tried ' . $possibleClass);

        return $possibleClass;
    }

    public static function pageNameSpace(ModuleProvider $module)
    {
        return $module->getNamespaceName() . '\Http\Pages';
    }

}