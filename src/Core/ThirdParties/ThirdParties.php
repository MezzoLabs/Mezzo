<?php


namespace MezzoLabs\Mezzo\Core\ThirdParties;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\ThirdParties\Wrappers;
use MezzoLabs\Mezzo\Core\ThirdParties\Wrappers\ThirdPartyWrapper;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

class ThirdParties extends Collection
{
    /**
     * @var string[]
     */
    protected $toLoad = [
        "DingoApi" => Wrappers\DingoApi::class,
        "Html" => Wrappers\Html::class,
        "EloquentSluggable" => Wrappers\EloquentSluggable::class,
        'UniqueWithValidator' => Wrappers\UniqueWithValidator::class
    ];

    /**
     * A Collection of the reflections
     *
     * @var ThirdPartyWrapper[]
     */
    protected $items = [];

    /**
     * @var Mezzo
     */
    private $mezzo;

    /**
     * The third party manager.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->mezzo = mezzo();
    }

    /**
     * Create the wrapper classes and put them into the collection
     */
    public function createWrappers()
    {
        foreach ($this->toLoad as $wrapperKey => $wrapperClass) {
            $wrapper = $this->createWrapper($wrapperClass);
            $this->put($wrapperKey, $wrapper);
        }

    }

    /**
     * @param $class
     * @return ThirdPartyWrapper
     */
    protected function createWrapper($class)
    {
        $wrapper = $this->mezzo->make($class);

        $this->mezzo->app()->instance(get_class($wrapper), $wrapper);

        return $wrapper;

    }

    /**
     * Register the wrapped package service providers
     */
    public function registerServiceProviders()
    {
        $this->map(function (ThirdPartyWrapper $wrapper) {
            $wrapper->register();
        });
    }

    /**
     * Prepare the configurations for each third party package before they boot.
     */
    public function overwriteConfigs()
    {
        $this->map(function (ThirdPartyWrapper $wrapper) {
            $wrapper->overwriteConfig();
        });
    }

    /**
     * Called when all the providers are booted and ready to take the request
     */
    public function onProvidersBooted()
    {
        $this->map(function (ThirdPartyWrapper $wrapper) {
            $wrapper->onProviderBooted();
        });
    }

    public function getOrFail($thirdParty)
    {
        if(!$this->has($thirdParty))
            throw new MezzoException('Cannot find third party with the name ' . $thirdParty);

        return $this->get($thirdParty);
    }

} 