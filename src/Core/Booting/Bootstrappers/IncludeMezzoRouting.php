<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Core\Mezzo;

class IncludeMezzoRouting implements Bootstrapper
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
        if (!$mezzo->app()->routesAreCached()) {

            $routesPath = mezzo_source_path() . 'Http/routes.php';

            if (!file_exists($routesPath)) {
                throw new \Exception('Mezzo routes file cannot be loaded. Tried to use ' . $routesPath);
            }

            require $routesPath;

        }
    }
}