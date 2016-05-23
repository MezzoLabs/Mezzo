<?php

namespace MezzoLabs\Mezzo\Core\Modularisation\Extensions;


interface ExtensibleModule
{
    /**
     * @return ModuleExtensionCollection
     */
    public function extensions();

    /**
     * Register a new extension
     *
     * @param $moduleExtension
     */
    public function registerExtension($moduleExtension);

    /**
     * Calls the boot method on all extensions
     */
    public function bootExtensions();

}