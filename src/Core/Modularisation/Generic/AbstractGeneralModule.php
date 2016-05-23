<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Generic;

use Illuminate\Foundation\Application;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

abstract class AbstractGeneralModule extends ModuleProvider
{
    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
} 