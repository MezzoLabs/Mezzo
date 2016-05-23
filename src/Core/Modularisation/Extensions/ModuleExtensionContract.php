<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Extensions;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

interface ModuleExtensionContract
{
    /**
     * The module that this extension is based on.
     *
     * @return ModuleProvider
     */
    public function module();

    /**
     * Boot the module extension up, load pages and make changes to the base module.
     */
    public function boot();
}