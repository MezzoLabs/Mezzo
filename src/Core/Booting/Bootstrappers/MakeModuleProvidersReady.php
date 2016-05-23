<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

class MakeModuleProvidersReady implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $mezzo->moduleCenter()->associateModels();

        $mezzo->moduleCenter()->modules()->each(function (ModuleProvider $moduleProvider) {
            $moduleProvider->ready();
        });
    }
}