<?php


namespace MezzoLabs\Mezzo\Core\ThirdParties\Wrappers;


use Cviebrock\EloquentSluggable\SluggableServiceProvider;

class EloquentSluggable extends ThirdPartyWrapper
{

    /**
     * Class string of the packages laravel provider.
     *
     * @var string
     */
    protected $provider = SluggableServiceProvider::class;


    /**
     * Prepare the configuration before a new service gets registered
     *
     * @return mixed
     */
    public function overwriteConfig()
    {

    }
}