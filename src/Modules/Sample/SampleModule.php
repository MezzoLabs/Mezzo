<?php


namespace MezzoLabs\Mezzo\Modules\Sample;


use App\Tutorial;
use App\User;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

class SampleModule extends ModuleProvider
{
    protected $models = [
        Tutorial::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Called when module is ready, model reflections are loaded.
     *
     * @return mixed
     */
    public function ready()
    {
        $tutorialReflection = $this->modelReflectionSets->get(Tutorial::class);

        //dd($tutorialReflection->relationships());
    }
}