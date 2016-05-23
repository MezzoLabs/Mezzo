<?php


namespace MezzoLabs\Mezzo\Listeners;


use Illuminate\Events\Dispatcher;

abstract class Listener
{

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}