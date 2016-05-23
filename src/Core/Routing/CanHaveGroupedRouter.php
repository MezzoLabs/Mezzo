<?php

namespace MezzoLabs\Mezzo\Core\Routing;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Exceptions\MezzoException;
use Dingo\Api\Routing\Router as DingoRouter;
use Illuminate\Routing\Router as LaravelRouter;



trait CanHaveGroupedRouter
{
    /**
     * @var LaravelRouter|DingoRouter
     */
    private $groupedRouter;

    /**
     * @return LaravelRouter|DingoRouter
     */
    public function getGroupedRouter()
    {
        return $this->groupedRouter;
    }

    /**
     * @param LaravelRouter|DingoRouter
     */
    public function setGroupedRouter($groupedRouter)
    {
        $this->groupedRouter = clone $groupedRouter;
    }

    /**
     * @return bool
     */
    public function hasGroupedRouter()
    {
        return isset($this->groupedRouter);
    }

    public function hasGroupedRouterOrFail()
    {
        return new MezzoException('No module set for ' . get_class($this));
    }
}