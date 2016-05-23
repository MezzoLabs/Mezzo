<?php


namespace MezzoLabs\Mezzo\Core\ThirdParties\Wrappers;


use Collective\Html\HtmlServiceProvider;

class Html extends ThirdPartyWrapper
{


    /**
     * Class string of the packages laravel provider.
     *
     * @var string
     */
    protected $provider = HtmlServiceProvider::class;

    /**
     * Prepare the configuration before a new service gets registered
     *
     * @return mixed
     */
    public function overwriteConfig()
    {

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
    }

    /**
     * Get the instance of this wrapper which is stored inside the thirdParties collection.
     *
     * @return Html
     */
    public static function make()
    {
        return mezzo()->make('mezzo.thirdParties')->get('Html');
    }

}