<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('Categories')->generateRoutes();

module_route('Categories', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('Category');
    $api->resource('CategoryGroup');

    $cockpit->post('categories/category', [
        'uses' => 'Controllers\CategoryController@store',
        'as' => 'category.store'
    ]);

    $cockpit->get('categories/category/destroy/{id}', [
        'uses' => 'Controllers\CategoryController@destroy',
        'as' => 'category.destroy'
    ]);
});


