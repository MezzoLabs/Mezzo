<?php


namespace MezzoLabs\Mezzo\Cockpit;


use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\AttributeRenderEngine as CockpitAttributeRenderer;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\FormBuilder;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderEngine as AttributeSchemaRenderer;

class CockpitProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCockpit();
        $this->registerRenderer();
        $this->loadViews();
        $this->publishPublicFolder();
    }

    /**
     * Registers the cockpit singleton instance.
     */
    protected function registerCockpit()
    {
        $this->app->singleton(Cockpit::class, function () {
            return new Cockpit($this, mezzo());
        });

        $this->app->alias(Cockpit::class, 'mezzo.cockpit');

        $this->app->singleton(FormBuilder::class, function (Application $app) {
            return new FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());
        });


    }

    private function registerRenderer()
    {
        app()->bind(AttributeSchemaRenderer::class, CockpitAttributeRenderer::class);
    }

    protected function loadViews()
    {
        $this->loadViewsFrom($this->resourcesFolder('/views'), 'cockpit');
    }

    private function resourcesFolder($folder = "")
    {
        return __DIR__ . '/resources' . $folder;
    }

    protected function publishPublicFolder()
    {
        $this->publishes([
            $this->publicFolder() => public_path('mezzolabs/mezzo/cockpit')
        ]);
    }

    private function publicFolder($folder = "")
    {
        return __DIR__ . '/public' . $folder;
    }

    public function boot()
    {
        $this->includeHelpers();
    }

    protected function includeHelpers()
    {
        require __DIR__ . '/helpers.php';
    }

    /**
     * @return Cockpit
     */
    public function cockpit()
    {
        return mezzo()->make(Cockpit::class);
    }

    /**
     * Include the basic routes for the cockpit.
     * Please note that this has nothing to do with the module routes.
     */
    public function includeRoutes()
    {
        $allModules = mezzo()->moduleCenter()->modules();

        $allModules->each(function (ModuleProvider $moduleProvider) {
            $moduleProvider->includeRoutes();
        });

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }
    }


}