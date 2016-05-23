<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages;

use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Controllers\DebugController;

class LogsPage extends ModulePage
{
    protected $options = [
        'visibleInNavigation' => true,
        'renderedByFrontend' => false
    ];
    protected $controller = DebugController::class;

    protected $action = 'logs';

    protected $view = 'modules.developer-dashboard::logs';

}