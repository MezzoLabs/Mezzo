<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Console\MezzoKernel;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

class RegisterConsoleCommands implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        $mezzo->make(MezzoKernel::class)->registerCoreCommands();

        $mezzo->moduleCenter()->modules()->each(function (ModuleProvider $module) {
            $module->loadCommands();
        });
    }
}