<?php

namespace MezzoLabs\Mezzo\Core\Routing;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

trait CanHaveModule
{
    /**
     * @var ModuleProvider
     */
    protected $module;

    /**
     * @return ModuleProvider
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param ModuleProvider $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return bool
     */
    public function hasModule()
    {
        return isset($this->module);
    }

    public function hasModuleOrFail()
    {
        return new MezzoException('No module set for ' . get_class($this));
    }
}