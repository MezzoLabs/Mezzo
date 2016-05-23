<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Core\Mezzo;

class IncludeCockpitRouting implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     * @throws \Exception
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $mezzo->makeCockpit()->serviceProvider()->includeRoutes();

        event('mezzo.cockpit.routes_included');
    }
}