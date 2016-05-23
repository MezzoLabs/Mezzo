<?php


namespace MezzoLabs\Mezzo\Listeners;


interface ListenerInterface
{
    /**
     * Handle the incomming event event.
     *
     * @param null $param
     * @return mixed
     */
    public function handle($param);
} 