<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('DeveloperDashboard')->generateRoutes();

module_route('DeveloperDashboard', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
});


