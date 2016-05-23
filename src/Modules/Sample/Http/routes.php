<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('Sample')->generateRoutes();

module_route('Sample', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('Tutorial');
});


