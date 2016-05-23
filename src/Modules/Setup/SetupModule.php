<?php


namespace MezzoLabs\Mezzo\Modules\Setup;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Modules\Installer\Setup\Install;

class SetupModule extends ModuleProvider
{

    protected $commands = [
        Install::class
    ];

    /**
     * Called when module is ready, model reflections are loaded.
     *
     * @return mixed
     */
    public function ready()
    {
        // TODO: Implement ready() method.
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}