<?php


namespace MezzoLabs\Mezzo\Core\Routing;


use Dingo\Api\Routing\Router as DingoRouter;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

class RoutesGenerator
{

    /**
     * The Dingo api router we will fill
     *
     * @var DingoRouter
     */
    public $apiRouter;

    /**
     * Fill the api router with the generated routes
     *
     * @param DingoRouter $apiRouter
     */
    public function run(DingoRouter $apiRouter)
    {

        $apiRouter->get('cheat', function () {
            return ['foo' => 'bar'];
        });
    }


} 