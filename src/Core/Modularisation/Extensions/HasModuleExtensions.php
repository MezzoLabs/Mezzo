<?php

namespace MezzoLabs\Mezzo\Core\Modularisation\Extensions;


trait HasModuleExtensions
{
    /**
     * @var ModuleExtensionCollection
     */
    protected $extensions = null;

    /**
     * @return ModuleExtensionCollection
     */
    public function extensions()
    {
        if (!$this->extensions)
            $this->extensions = new ModuleExtensionCollection();

        return $this->extensions;
    }

    /**
     * Register a new extension
     *
     * @param ModuleExtensionContract|string|array $moduleExtensionClass
     */
    public function registerExtension($moduleExtensionClass)
    {
        // Check if the given variable is an array. Register them one by one.
        if (is_array($moduleExtensionClass)) {
            foreach ($moduleExtensionClass as $current)
                $this->registerExtension($current);
            return;
        }

        $moduleExtension = app()->register($moduleExtensionClass);

        $this->extensions()->put($moduleExtensionClass, $moduleExtension);
    }

    /**
     * Calls the boot method on all extensions
     */
    public function bootExtensions()
    {
        $this->extensions()->boot();
    }
}