<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Extensions;


use Illuminate\Support\ServiceProvider;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleCenter;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Modules\General\GeneralModule;

abstract class ModuleExtension extends ServiceProvider implements ModuleExtensionContract
{
    /**
     * The pages that will be added to the module.
     *
     * @var array
     */
    protected $pages = [

    ];

    /**
     * @var ModuleProvider
     */
    protected $moduleInstance;

    /**
     * @var string
     */
    protected $module = GeneralModule::class;

    /**
     * @var Mezzo
     */
    protected $mezzo;

    /**
     * @var ModuleCenter
     */
    protected $moduleCenter;

    /**
     * The module that this extension is based on.
     *
     * @return ModuleProvider
     */
    public function module()
    {
        if (!$this->moduleInstance) {
            $this->moduleInstance = mezzo()->module($this->module);
        }

        return $this->moduleInstance;
    }

    public static function make()
    {

    }

    /**
     * Boot the module extension up, load pages and make changes to the base module.
     */
    public function boot()
    {
        $this->addPages($this->pages);
    }

    /**
     * Adds a couple of pages to the module.
     *
     * @param array $pages
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     * @throws \MezzoLabs\Mezzo\Exceptions\ModulePageException
     */
    protected function addPages(array $pages)
    {
        foreach ($pages as $pageClass) {
            $page = $this->module()->makePage($pageClass);

            $this->module()->pages()->add($page);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mezzo = mezzo();
        $this->moduleCenter = $this->mezzo->moduleCenter();
    }


}