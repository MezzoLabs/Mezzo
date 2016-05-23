<?php


namespace MezzoLabs\Mezzo\Contracts;


interface Model
{
    /**
     * Returns the class name of the module which contains this model.
     *
     * @return string
     */
    public function getModuleClass();
} 