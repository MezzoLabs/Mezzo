<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Extensions;


use MezzoLabs\Mezzo\Core\Collection\StrictCollection;

class ModuleExtensionCollection extends StrictCollection
{
    public function boot()
    {
        $this->each(function (ModuleExtensionContract $moduleExtension) {
            $moduleExtension->boot();
        });
    }

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof ModuleExtensionContract;
    }
}