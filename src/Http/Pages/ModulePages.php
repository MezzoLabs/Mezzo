<?php


namespace MezzoLabs\Mezzo\Http\Pages;


use Illuminate\Filesystem\ClassFinder;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Exceptions\ModulePageException;

class ModulePages extends Collection
{
    public function collectFromModule(ModuleProvider $module)
    {
        $pagesFolder = $module->path() . '/Http/Pages/';

        $this->collectFromFolder($pagesFolder, $module);
    }

    public function collectFromFolder($folder, $module)
    {
        if (!is_dir($folder))
            return false;

        $pageClasses = (new ClassFinder())->findClasses($folder);
        foreach ($pageClasses as $pageClass) {
            if (!class_exists($pageClass)) {
                throw new ModulePageException($pageClass . ' is not a valid class. Maybe you need a "composer dump-autoload".');
            }

            if (!is_subclass_of($pageClass, ModulePage::class) ){
                throw new ModulePageException($pageClass . ' is not a valid module page.');
            }

            $this->add(new $pageClass($module));
        }
    }

    public function add(ModulePageContract $modulePage)
    {
        if ($this->has($modulePage->name())) {
            throw new ModulePageException('The page ' . $modulePage->name() . ' is already registered for this module.');
        }

        $this->put($modulePage->name(), $modulePage);
    }

    public function registerRoutes()
    {
        $this->each(function (ModulePage $page) {
            $page->registerRoutes();
        });
    }

    public function filterVisibleInNavigation()
    {
        return $this->filter(function(ModulePage $page){
            return $page->isVisibleInNavigation();
        });
    }

    public function filterAllowed()
    {
        return $this->filter(function (ModulePage $page) {
            return $page->isAllowed();
        });
    }

    public function sortByOrder()
    {
        return $this->sortBy(function (ModulePage $page) {
            return $page->order();
        });
    }
}