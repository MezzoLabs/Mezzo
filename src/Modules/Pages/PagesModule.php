<?php


namespace MezzoLabs\Mezzo\Modules\Pages;


use App\Page;
use App\User;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;

class PagesModule extends ModuleProvider
{
    protected $models = [
        Page::class,
    ];

    /**
     * Options that will determine the style of this module.
     *
     * @var array|Collection
     */
    protected $options = [
        'visible' => true
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViews();
    }

    /**
     * Called when module is ready, model reflections are loaded.
     *
     * @return mixed
     */
    public function ready()
    {
    }
}