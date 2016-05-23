<?php


namespace MezzoLabs\Mezzo\Modules\User;

use App\Permission;
use App\Role;
use App\User;
use App\UserMeta;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Modules\User\Commands\SeedPermissions;

class UserModule extends ModuleProvider
{
    protected $group = "admin";

    protected $options = [
        'permissions' => 'administrate'
    ];

    protected $commands = [
        SeedPermissions::class
    ];

    protected $models = [
        User::class,
        Role::class,
        Permission::class,
        //UserMeta::class
    ];

    protected $transformers = [

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
        $this->loadViews();
    }
}