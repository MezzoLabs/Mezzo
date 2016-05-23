<?php


namespace MezzoLabs\Mezzo\Core\Routing;


class ApiExceptionHandler
{
    /**
     * @param callable $callback
     */
    public function register(callable $callback)
    {
        $this->dingoExceptionHandler()->register($callback);
    }

    /**
     * @return \Dingo\Api\Exception\Handler
     */
    public function dingoExceptionHandler()
    {
        return app('Dingo\Api\Exception\Handler');
    }
}