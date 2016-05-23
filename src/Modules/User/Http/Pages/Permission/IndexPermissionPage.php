<?php


namespace MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission;

use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;

class IndexPermissionPage extends IndexResourcePage
{
    protected $options = [
        'permissions' => 'administrate'
    ];

    public function boot()
    {
        $this->options('permissions', 'administrate');
    }
}