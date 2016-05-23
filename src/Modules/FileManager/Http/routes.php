<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;

module_route('FileManager', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {

    $file = mezzo()->model('File');
    $fileManager = $router->getModule();
    $controller = $fileManager->apiResourceController('FileApiController');
    $api->post($api->modelUri($file) . '/upload', $controller->qualifiedActionName('upload'));

    Route::get('mezzo/upload/{path?}', [
        'uses' => '\MezzoLabs\Mezzo\Modules\FileManager\Http\Controllers\PublishFilesController@publish',
        'as' => 'publish'
    ])->where('path', '.+');

    $router->getModule()->generateRoutes();
    $api->resource('File');
    $api->resource('ImageFile');

});