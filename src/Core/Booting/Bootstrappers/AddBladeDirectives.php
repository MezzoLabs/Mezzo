<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Cockpit\CockpitProvider;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Templating\BladeDirectives;

class AddBladeDirectives implements Bootstrapper
{


    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {

        $bladeDirectives = app(BladeDirectives::class);
        $bladeDirectives->addDirectives();

    }
}