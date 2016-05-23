<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages;

use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Controllers\RoutesController;

class RoutesPage extends ModulePage
{
    protected $options = [
        'visibleInNavigation' => true,
        'renderedByFrontend' => false
    ];
    protected $controller = RoutesController::class;

    protected $action = 'show';

    protected $view = 'modules.developer-dashboard::routes';

}