<?php

namespace MezzoLabs\Mezzo\Http\Pages;


use Illuminate\Support\Collection;

trait HasModulePageExtensions
{
    protected $extensions;

    protected static $registeredExtensions = [];

    public static function registerExtension($class)
    {
        static::$registeredExtensions[$class] = $class;
    }

    protected function makeExtensions()
    {
        $extensions = new Collection();

        foreach (static::$registeredExtensions as $class) {
            $extension = $this->makeExtension($class);
            $extensions->put(get_class($extension), $extension);
        }

        $this->extensions = $extensions;
    }

    private function makeExtension($class) : ModulePageExtension
    {
        if (!is_string($class))
            return $class;

        return app()->make($class, ['page' => $this]);
    }

    protected function renderSectionExtensions($sectionName)
    {

        $view = "";
        foreach ($this->sectionViews($sectionName) as $viewKey) {
            $view .= view($viewKey, $this->latestData);
        }

        return $view;
    }

    protected function sectionViews($sectionName)
    {
        $sectionViews = [];
        $this->extensions()->each(function (ModulePageExtension $extension) use (&$sectionViews, $sectionName) {
            $sectionViews = array_merge($extension->sectionViews($sectionName));
        });

        return $sectionViews;
    }

    protected function extensionsData($controllerData)
    {
        $data = [];

        $this->extensions()->each(function (ModulePageExtension $extension) use (&$data, $controllerData) {
            $data = array_merge($extension->data($controllerData));
        });

        return $data;
    }

    /**
     * @return Collection
     */
    public function extensions()
    {
        return $this->extensions;
    }


}