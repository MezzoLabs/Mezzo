<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;
use MezzoLabs\Mezzo\Modules\General\Http\Pages\IndexOptionsPage;

module_route('General', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {

    $router->getModule()->generateRoutes();

    $api->resource('Option');

    $cockpit->page(IndexOptionsPage::class);

    $cockpit->post('general/options', [
        'uses' => 'Controllers\OptionController@store',
        'as' => 'option.store'
    ]);

    $cockpit->delete('general/options/{id}', [
        'uses' => 'Controllers\OptionController@delete',
        'as' => 'option.delete'
    ]);
});
