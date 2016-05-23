<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('Pages')->generateRoutes();

module_route('Pages', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('Page');

    $cockpit->post('pages/page', [
        'uses' => 'Controllers\PageController@store',
        'as' => 'page.store'
    ]);

});


