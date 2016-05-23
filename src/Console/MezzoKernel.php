<?php

namespace MezzoLabs\Mezzo\Console;

use Illuminate\Support\ServiceProvider;
use MezzoLabs\Mezzo\Console\Commands\MezzoCommand;
use MezzoLabs\Mezzo\Core\Mezzo;

class MezzoKernel
{
    /**
     * The Artisan commands provided by Mezzo.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * @var ServiceProvider
     */
    protected $mezzoServiceProvider;

    /**
     * @var Mezzo
     */
    protected $mezzo;

    public function __construct(Mezzo $mezzo)
    {

        $this->mezzoServiceProvider = $mezzo->serviceProvider;
        $this->mezzo = $mezzo;
    }

    public function registerCoreCommands()
    {
        $this->registerCommands($this->commands);
    }

    public function registerCommands($commands = [])
    {
        foreach ($commands as $command) {
            $this->registerCommand($this->makeCommand($command));
        }
    }

    protected function registerCommand(MezzoCommand $command)
    {
        $this->mezzo->app()->instance($command->abstractName(), $command);

        $command->setMezzo($this->mezzo);

        $this->mezzoServiceProvider->commands($command->abstractName());
    }

    /**
     * @param $className
     * @return MezzoCommand
     */
    protected function makeCommand($className)
    {
        $instance = $this->mezzo->make($className);
        return $instance;
    }

    /**
     * @return array
     */
    public function commands()
    {
        return $this->commands;
    }


}