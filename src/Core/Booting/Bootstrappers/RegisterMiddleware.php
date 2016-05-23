<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Console\MezzoKernel;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Http\Middleware\Authenticate;
use MezzoLabs\Mezzo\Http\Middleware\MezzoMiddleware;
use MezzoLabs\Mezzo\Http\Middleware\RedirectIfAuthenticated;

class RegisterMiddleware implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        MezzoMiddleware::register([
            Authenticate::class,
            RedirectIfAuthenticated::class
        ]);
    }
}