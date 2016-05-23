<?php


namespace MezzoLabs\Mezzo\Core\ThirdParties\Wrappers;


use Felixkiss\UniqueWithValidator\UniqueWithValidatorServiceProvider;

class UniqueWithValidator extends ThirdPartyWrapper
{

    /**
     * Class string of the packages laravel provider.
     *
     * @var string
     */
    protected $provider = UniqueWithValidatorServiceProvider::class;

    /**
     * Prepare the configuration before a new service gets registered
     *
     * @return mixed
     */
    public function overwriteConfig()
    {

    }
}