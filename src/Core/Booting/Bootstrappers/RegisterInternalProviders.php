<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Core\Logging\LoggingServiceProvider;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Providers\EventServiceProvider;

class RegisterInternalProviders implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $mezzo->app()->register(LoggingServiceProvider::class);
        $mezzo->app()->register(EventServiceProvider::class);
    }
}