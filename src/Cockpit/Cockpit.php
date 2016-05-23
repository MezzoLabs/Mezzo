<?php


namespace MezzoLabs\Mezzo\Cockpit;


use MezzoLabs\Mezzo\Cockpit\Html\Rendering\AttributeRenderEngine;
use MezzoLabs\Mezzo\Cockpit\Http\Controllers\MainController;
use MezzoLabs\Mezzo\Core\Mezzo;

class Cockpit
{
    /**
     * @var CockpitProvider
     */
    protected $serviceProvider;

    /**
     * @var Mezzo
     */
    protected $mezzo;

    public function __construct(CockpitProvider $serviceProvider, Mezzo $mezzo)
    {
        $this->serviceProvider = $serviceProvider;
        $this->mezzo = $mezzo;
    }

    /**
     * @return CockpitProvider
     */
    public function serviceProvider()
    {
        return $this->serviceProvider;
    }

    /**
     * @return string
     */
    public function startAction()
    {
        return '\\' . MainController::class . '@start';
    }

    /**
     * Registers a handler that determines the look of an attribute in the cockpit.
     *
     * @param $handlerClass
     * @return void
     */
    public function registerAttributeRenderer($handlerClass)
    {
        AttributeRenderEngine::registerHandler($handlerClass);
    }


}