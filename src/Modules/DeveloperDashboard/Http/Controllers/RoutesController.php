<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Controllers;


use MezzoLabs\Mezzo\Http\Controllers\CockpitController;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\CommandWrappers\ApiRouteListCommandWrapper;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\CommandWrappers\RouteListCommandWrapper;
use MezzoLabs\Mezzo\Modules\DeveloperDashboard\Http\Pages\RoutesPage;

class RoutesController extends CockpitController
{
    public function show()
    {
        $applicationRoutes = RouteListCommandWrapper::make()->getApplicationRoutes();
        $apiRoutes = ApiRouteListCommandWrapper::make()->getApiRoutes();

        //mezzo_dump($apiRoutes);
        //mezzo_dd($applicationRoutes);

        return $this->page(RoutesPage::class, [
            'applicationRoutes' => $applicationRoutes,
            'apiRoutes' => $apiRoutes
        ]);

    }
}