<?php


namespace MezzoLabs\Mezzo\Core\Routing;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Exceptions\RoutingException;

class ApiConfig
{
    /**
     * @var Collection
     */
    protected $entries;

    /**
     * Creates the container for the API configuration.
     */
    public function __construct()
    {
        $configArray = mezzo()->config('api');

        $this->entries = new Collection($configArray);
    }

    public static function make()
    {
        return mezzo()->make(ApiConfig::class);
    }

    /**
     * Get an entry from the API config.
     *
     * @param string $key
     * @return $this|mixed
     * @throws RoutingException
     */
    public function get($key = "")
    {
        if (empty($key)) return $this;

        if (!$this->has($key))
            throw new RoutingException($key . ' is not a valid part of the API config . ');

        return $this->entries->get($key);
    }

    /**
     * Check if this API config is set.
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->entries->has($key);
    }

    /**
     * @param $overwriteWith
     * @return Collection
     */
    public function merge($overwriteWith)
    {
        return $this->entries->merge($overwriteWith);
    }
}