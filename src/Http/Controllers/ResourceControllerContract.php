<?php


namespace MezzoLabs\Mezzo\Http\Controllers;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;

interface ResourceControllerContract
{
    /**
     * @return MezzoModelReflection
     */
    function model();

    /**
     * @return ModelRepository
     */
    function repository();
}