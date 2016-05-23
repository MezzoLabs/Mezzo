<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard\CommandWrappers;


use Dingo\Api\Console\Command\Routes as DingoRoutesCommand;
use Illuminate\Support\Collection;

class ApiRouteListCommandWrapper extends DingoRoutesCommand
{
    /**
     * @return ApiRouteListCommandWrapper
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @return Collection
     */
    public function getApiRoutes()
    {
        return new Collection($this->getRoutes());
    }

    public function option($key = null)
    {
        return null;
    }
}