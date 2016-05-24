<?php


namespace MezzoLabs\Mezzo\Core\Helpers;


class DebugService
{
    /**
     * @var \Debugbar\Debugbar
     */
    protected $debugbar;

    /**
     * DebugService constructor.
     */
    public function __construct()
    {
        $this->debugbar = (app()->bound('debugbar')) ? app('debugbar') : null;
    }

    public function getQueries() : array
    {
        if (!$this->debugbar) {
            return [];
        }

        return $this->debugbar->getCollector('queries')->collect();
    }

    /**
     * @param $name
     * @param $label
     */
    public function startMeasure($name, $label)
    {
        if(!$this->debugbar){
            return;
        }

        start_measure($name, $label);
    }

    public function stopMeasure($name)
    {
        if(!$this->debugbar){
            return;
        }

        stop_measure($name);
    }
}