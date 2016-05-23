<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Cockpit\CockpitProvider;
use MezzoLabs\Mezzo\Core\Mezzo;

class RegisterCockpitProvider implements Bootstrapper
{


    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $mezzo->app()->register(CockpitProvider::class);
    }
}