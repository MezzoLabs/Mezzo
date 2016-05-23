<?php

return [
    "hello" => "there",

    "moduleProviders" => [
        \MezzoLabs\Mezzo\Modules\Pages\PagesModule::class,
        //\MezzoLabs\Mezzo\Modules\Sample\SampleModule::class,
        \MezzoLabs\Mezzo\Modules\User\UserModule::class,
        \MezzoLabs\Mezzo\Modules\Generator\GeneratorModule::class,
        \MezzoLabs\Mezzo\Modules\DeveloperDashboard\DeveloperDashboardModule::class,
        \MezzoLabs\Mezzo\Modules\Categories\CategoriesModule::class,
        \MezzoLabs\Mezzo\Modules\Contents\ContentsModule::class,
        \MezzoLabs\Mezzo\Modules\Posts\PostsModule::class,
        \MezzoLabs\Mezzo\Modules\FileManager\FileManagerModule::class,
        \MezzoLabs\Mezzo\Modules\Addresses\AddressesModule::class,
        \MezzoLabs\Mezzo\Modules\Setup\SetupModule::class

    ],

    "api" => [
        'prefix' => 'api',
        'version' => 'v1',
        'vendor' => 'MezzoLabs',
        'debug' => env('APP_DEBUG', false),
        'strict' => true,
        'defaultFormat' => 'json',
        'domain' => null,
        'formats' => [

            'json' => MezzoLabs\Mezzo\Http\Format\Json::class

        ]
    ],

    'filemanager' => [
        'active_disk' => env('MEZZO_FILEMANAGER_ACTIVE_DISK', 'local'),
        'disks' => [
            'local' => [
                'folder' => env('MEZZO_FILEMANAGER_LOCAL_FOLDER', storage_path('mezzo/upload')),
            ],
            's3' => [
                'base_url' => 'https://s3.eu-central-1.amazonaws.com/mezzo.test'
            ],
        ]
    ],

    'cockpit' => [
        'prefix' => 'mezzo',
        'namedRouteNamespace' => 'cockpit::'
    ],

    'moduleGroups' => [
        'general' => 'General',
        'admin' => 'Admin',
        'development' => 'Development'
    ],

    'google_maps_api' => 'AIzaSyBm2n4bQfABTkiGxQp7e-QvWRPQhvAhjGM',



];
  