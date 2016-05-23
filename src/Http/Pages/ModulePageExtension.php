<?php


namespace MezzoLabs\Mezzo\Http\Pages;


abstract class ModulePageExtension
{
    protected $sectionViews = [];

    /**
     * @var ModulePageContract
     */
    protected $page;

    /**
     * An array of variables that will be shared to the view.
     *
     * @return array
     */
    abstract public function data($controllerData) : array;

    abstract public function boot();

    public function __construct(ModulePageContract $page)
    {

        $this->page = $page;

        $this->boot();
    }

    /**
     * @return ModulePageContract
     */
    public function page()
    {
        return $this->page;
    }

    protected function addViewToSection(string $sectionName, string $viewKey)
    {
        if (!isset($this->sectionViews[$sectionName]))
            $this->sectionViews[$sectionName] = [];

        $this->sectionViews[$sectionName][] = $viewKey;
    }

    public function sectionViews($sectionName) : array
    {
        if (!isset($this->sectionViews[$sectionName]))
            return [];

        return $this->sectionViews[$sectionName];
    }
}