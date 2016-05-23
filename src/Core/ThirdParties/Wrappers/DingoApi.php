<?php


namespace MezzoLabs\Mezzo\Core\ThirdParties\Wrappers;


use Dingo\Api\Provider\LaravelServiceProvider as DingoProvider;
use Dingo\Api\Routing\Router as DingoRouter;
use MezzoLabs\Mezzo\Core\Routing\ApiRouter;
use MezzoLabs\Mezzo\Exceptions\RoutingException;

class DingoApi extends ThirdPartyWrapper
{
    /**
     * Class string of the packages laravel provider.
     *
     * @var string
     */
    protected $provider = DingoProvider::class;

    /**
     * The Dingo Api router
     *
     * @var DingoRouter
     */
    private $dingoRouter;

    /**
     * Get the instance of this wrapper which is stored inside the thirdParties collection.
     *
     * @return DingoApi
     */
    public static function make()
    {
        return mezzo()->makeThirdparties()->getOrFail('DingoApi');
    }

    /**
     * Prepare the configuration before a new service gets registered
     *
     * @return mixed
     */
    public function overwriteConfig()
    {
        $this->mezzoConfig->overwrite('api', 'mezzo.api');
    }

    /**
     * Called when the package service provider got booted. (We listened carefully)
     *
     * @return mixed
     */
    public function onProviderBooted()
    {
        if ($this->booted) return false;
        parent::onProviderBooted();

        $this->dingoRouter = $this->mezzo->make(DingoRouter::class);
    }

    /**
     * @return ApiRouter
     */
    public function getDingoRouter()
    {
        if(!$this->dingoRouter)
            throw new RoutingException('Cannot get dingo router. Maybe the package is not yet loaded.');

        return $this->dingoRouter;
    }

}