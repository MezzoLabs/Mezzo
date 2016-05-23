<?php


namespace MezzoLabs\Mezzo\Core\Booting\Bootstrappers;


use Illuminate\Database\Eloquent\Model;
use MezzoLabs\Mezzo\Core\Mezzo;

class SetEloquentDispatcher implements Bootstrapper
{

    /**
     * Run the booting process for this service.
     *
     * @param Mezzo $mezzo
     * @return mixed
     */
    public function bootstrap(Mezzo $mezzo)
    {
        Model::setEventDispatcher(app('events'));
    }
}