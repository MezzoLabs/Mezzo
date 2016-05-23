<?php


namespace MezzoLabs\Mezzo\Listeners\Early;

use MezzoLabs\Mezzo\Listeners\Listener;
use MezzoLabs\Mezzo\Listeners\ListenerInterface;

class DispatchBeforeProvidersBoot extends Listener implements ListenerInterface
{
    /**
     * Handle the event.
     *
     * @param
     * @return void
     */
    public function handle($app)
    {
        mezzo()->make('mezzo.config')->beforeProvidersBoot();
    }
} 