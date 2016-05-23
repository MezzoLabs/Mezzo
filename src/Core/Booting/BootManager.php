<?php


namespace MezzoLabs\Mezzo\Core\Booting;


use Illuminate\Contracts\Foundation\Application;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\AddBladeDirectives;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\IncludeCockpitRouting;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\IncludeMezzoRouting;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\IncludeThirdParties;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\LoadConfiguration;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\MakeModuleProvidersReady;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterBindings;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterCockpitProvider;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterConsoleCommands;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterInternalProviders;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterMiddleware;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterModuleProviders;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RegisterTransformers;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\RunThirdPartyWrappers;
use MezzoLabs\Mezzo\Core\Booting\Bootstrappers\SetEloquentDispatcher;
use MezzoLabs\Mezzo\Core\Mezzo;

class BootManager
{

    const RegisterPhase = "registerPhase";
    const BootPhase = "bootPhase";
    const BootedPhase = "bootedPhase";

    /**
     * The Laravel Application
     *
     * @var Application
     */
    protected $app;

    /**
     * The mezzo instance.
     *
     * @var Application
     */
    protected $mezzo;

    /**
     * If true this will output the boot order
     *
     * @var bool
     */
    protected $debug = true;


    /**
     * Bootstrappers split into the different phases of the MezzoServiceProvider.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        "registerPhase" => [
            SetEloquentDispatcher::class,
            RegisterBindings::class,
            LoadConfiguration::class,
            RegisterInternalProviders::class,
            RegisterModuleProviders::class,
            RegisterCockpitProvider::class,
            RegisterConsoleCommands::class,
            IncludeThirdParties::class
        ],
        "bootPhase" => [
        ],
        "bootedPhase" => [
            RunThirdPartyWrappers::class,
            MakeModuleProvidersReady::class,
            RegisterMiddleware::class,
            IncludeMezzoRouting::class,
            IncludeCockpitRouting::class,
            AddBladeDirectives::class,
            RegisterTransformers::class
        ]
    ];


    /**
     * @param Mezzo $mezzo
     * @internal param Application $app
     */
    public function __construct(Mezzo $mezzo)
    {
        $this->mezzo = $mezzo;
        $this->app = $mezzo->app();
    }

    /**
     * Return a BootManager instance
     *
     * @return BootManager
     */
    public static function make($mezzo)
    {
        return new BootManager($mezzo);
    }

    /**
     * Run the bootstrappers that are needed during the "register" phase
     */
    public function runRegisterPhase()
    {
        $this->bootForPhase(BootManager::RegisterPhase);
    }

    /**
     * Run the bootstrappers for the current phase
     *
     * @param string $phase
     */
    public function bootForPhase($phase = BootManager::RegisterPhase)
    {
        $bootstrappers = $this->bootstrappers[$phase];
        $this->run($bootstrappers);
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  string[] $bootstrappers
     * @return void
     */
    protected function run(array $bootstrappers)
    {
        foreach ($bootstrappers as $bootstrapper) {
            event('bootstrapping: ' . $bootstrapper, [$this->app]);

            if ($this->debug)
                \Debugbar::startMeasure($bootstrapper, $bootstrapper);

            $this->app->make($bootstrapper)->bootstrap($this->mezzo);

            if ($this->debug)
                \Debugbar::stopMeasure($bootstrapper);


            event('bootstrapped: ' . $bootstrapper, [$this->app]);
        }
    }

    /**
     * Run the bootstrappers that are needed during the "boot" phase
     */
    public function runBootPhase()
    {
        $this->bootForPhase(BootManager::BootPhase);
    }

    /**
     * Run the bootstrappers that are needed last.
     */
    public function bootedPhase()
    {
        $this->bootForPhase(BootManager::BootedPhase);
    }


} 