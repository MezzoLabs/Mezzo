<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use MezzoLabs\Mezzo\Core\Mezzo;

interface Bootstrapper
{
    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo);
} 