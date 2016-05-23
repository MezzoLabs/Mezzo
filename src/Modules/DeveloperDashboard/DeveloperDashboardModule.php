<?php


namespace MezzoLabs\Mezzo\Modules\DeveloperDashboard;


use App\Tutorial;
use App\User;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

class DeveloperDashboardModule extends ModuleProvider
{
    protected $group = "development";

    protected $models = [
    ];

    protected $options = [
        'icon' => 'ion-hammer',
        'permissions' => 'developer'
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

    public function boot()
    {
        $this->loadViews();
    }

}