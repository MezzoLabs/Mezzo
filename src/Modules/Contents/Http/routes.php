<?php

use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Core\Routing\CockpitRouter;
use MezzoLabs\Mezzo\Core\Routing\Router;
use MezzoLabs\Mezzo\Modules\Contents\Html\BlockFormHelper;
use MezzoLabs\Mezzo\Modules\Contents\Http\ApiControllers\ContentBlockTypeApiController;

module_route('Contents', [], function (Router $router, ApiRouter $api, CockpitRouter $cockpit) {
    $api->resource('Content');
    $api->resource('ContentBlock');
    $api->resource('ContentField');

    $typeController = '\\' . ContentBlockTypeApiController::class;
    $api->get('content-block-types', $typeController . '@index');
    $api->get('content-block-types/{hash}', $typeController . '@show');
    $cockpit->get('content-block-types/{hash}.html', [
        'uses' => $typeController . '@show',
        'as' => 'contents.block-type.html'
    ]);


});

if (!function_exists('content_block_form')) {

    function content_block_form($block)
    {
        return app(BlockFormHelper::class, ['block' => $block]);
    }
}


