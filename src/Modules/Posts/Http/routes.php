<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

mezzo()->module('Posts')->generateRoutes();

module_route('Posts', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('Post');

    $cockpit->post('posts/post', [
        'uses' => 'Controllers\PostController@store',
        'as' => 'post.store'
    ]);

    $api->action('locked', 'Post', ['mode' => 'single']);
    $api->action('lock', 'Post', ['mode' => 'single']);
    $api->action('unlock', 'Post', ['mode' => 'single']);

});


