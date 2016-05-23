<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages;

use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Controllers\DebugController;

class DebugPage extends ModulePage
{
    protected $options = [
        'visibleInNavigation' => true,
        'renderedByFrontend' => false
    ];
    protected $controller = DebugController::class;

    protected $action = 'show';

    protected $view = 'modules.developer-dashboard::debug';

}