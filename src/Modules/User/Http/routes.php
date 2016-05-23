<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('User')->generateRoutes();

module_route('User', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('User');
    $api->resource('Permission');
    $api->resource('Role');


    //$api->relation('User', 'roles');

    $cockpit->put('role/update/{id}',
        ['uses' => 'Controllers\RoleController@update', 'as' => 'role.update']
    );
});


