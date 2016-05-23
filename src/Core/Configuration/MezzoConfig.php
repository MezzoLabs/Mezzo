<?php


namespace MezzoLabs\Mezzo\Core\Configuration;


use Illuminate\Config\Repository;
use MezzoLabs\Mezzo\Core\Mezzo;
use MezzoLabs\Mezzo\Core\ThirdParties\ThirdParties;

class MezzoConfig
{
    /**
     * @var Mezzo
     */
    private $mezzo;
    /**
     * @var ThirdParties
     */
    private $thirdParties;
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @param Mezzo $mezzo
     * @param ThirdParties $thirdParties
     * @param Repository $repository
     */
    function __construct(Mezzo $mezzo, ThirdParties $thirdParties, Repository $repository)
    {
        $this->mezzo = $mezzo;
        $this->thirdParties = $thirdParties;
        $this->repository = $repository;
    }

    /**
     * Get a value from the mezzo config. Just like \Config::get, but the "mezzo" prefix is automatically added.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->repository()->get('mezzo.' . $key, $default);
    }

    /**
     * Load the Mezzo specific configuration.
     */
    public function load()
    {
        $this->mergeConfig();
    }

    /**
     * Merge the config from mezzo with the one of the application
     */
    protected function mergeConfig()
    {
        $this->mezzo->serviceProvider->mergeConfigFrom(__DIR__ . '../../../../config/config.php', 'mezzo');
    }

    /**
     * Called after all the providers registered and before one provider boots.
     */
    public function beforeProvidersBoot()
    {
        $this->thirdParties->overwriteConfigs();
    }


    /**
     * Get the Laravel config repository.
     *
     * @return Repository
     */
    public function repository()
    {
        return $this->repository;
    }

    /**
     * Overwrite one config key with another. Needed for overwriting config files at runtime.
     *
     * @param $weakKey
     * @param $strongKey
     */
    public function overwrite($weakKey, $strongKey)
    {
        $weakSettings = $this->repository->get($weakKey);
        $strongSettings = $this->repository->get($strongKey);

        $overwrittenSettings = array_merge($weakSettings, $strongSettings);

        $this->repository->set($weakKey, $overwrittenSettings);

    }



}