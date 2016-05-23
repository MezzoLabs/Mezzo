<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('Addresses')->generateRoutes();

module_route('Addresses', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('Address');
});


