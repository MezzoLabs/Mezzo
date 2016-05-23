<?php


namespace MezzoLabs\Mezzo\Listeners\Early;


use MezzoLabs\Mezzo\Listeners\Listener;
use MezzoLabs\Mezzo\Listeners\ListenerInterface;

class DispatchAfterProvidersBooted extends Listener implements ListenerInterface
{
    /**
     * Handle the event.
     *
     * @param
     * @return void
     */
    public function handle($param = null)
    {
        mezzo()->onAllProvidersBooted();
    }
} 