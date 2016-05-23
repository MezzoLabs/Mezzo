<?php

namespace MezzoLabs\Mezzo;

use Illuminate\Support\ServiceProvider;
use MezzoLabs\Mezzo\Core\Mezzo;


class MezzoServiceProvider extends ServiceProvider
{
    /**
     * The Mezzo core.
     *
     * @var Mezzo
     */
    protected $mezzo;


    /**
     * Register any package services.
     *
     * We have to boot Mezzo here because we will include third party providers during the boot process.
     * Before they start we want to add some additional settings.
     *
     * @return void
     */
    public function register()
    {
        $this->mezzo = require __DIR__ . '/../bootstrap/mezzo.php';

        $this->mezzo->serviceProvider = $this;

        $this->mezzo->onProviderRegistered();
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mezzo->onProviderBooted();
    }


    /**
     * Merge config from application with the one in the config folder.
     *
     * @param string $path
     * @param string $key
     */
    public function mergeConfigFrom($path, $key)
    {
        parent::mergeConfigFrom($path, $key);
    }

}