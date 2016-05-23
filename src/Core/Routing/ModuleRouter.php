<?php


namespace MezzoLabs\Mezzo\Core\Routing;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Routing\Router as MezzoRouter;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Pages\ModulePage;

class ModuleRouter
{
    /**
     * @var ModuleProvider
     */
    protected $module;

    /**
     * @var MezzoRouter
     */
    protected $mezzoRouter;

    /**
     * @param ModuleProvider $module
     */
    public function __construct(ModuleProvider $module)
    {
        $this->module = $module;
    }

    /**
     * @return ModuleProvider
     */
    public function module()
    {
        return $this->module;
    }

    /**
     * Try to include the routes.php inside the Http folder of the module.
     *
     * @throws ModuleControllerException
     */
    public function includeRoutesFile()
    {
        $routesPath = $this->module->path() . '/Http/routes.php';

        if (!file_exists($routesPath))
            throw new ModuleControllerException('Cannot find routes file for module "' .
                $this->module->qualifiedName() . '". Tried "' . $routesPath . '"');

        $module = $this->module();

        require $routesPath;
    }

    /**
     * Create a grouped mezzo router.
     *
     * @return Router
     */
    public function mezzoRouter()
    {
        if (!$this->mezzoRouter) {
            module_route($this->module, [], function (MezzoRouter $mezzoRouter) {
                $this->mezzoRouter = $mezzoRouter;
            });
        }

        return $this->mezzoRouter;
    }

    /**
     * Register the cockpit routes for a module page.
     *
     * @param ModulePage $modulePage
     */
    public function registerPage(ModulePage $modulePage)
    {
        $this->mezzoRouter()->cockpitRouter()->page($modulePage);
    }

    public function generateRoutes()
    {

        $this->module->pages()->registerRoutes();
    }


}