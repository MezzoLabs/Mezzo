<?php


namespace MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission;

use MezzoLabs\Mezzo\Cockpit\Pages\Resources\CreateResourcePage;

class CreatePermissionPage extends CreateResourcePage
{
    public function boot()
    {
        $this->options('visibleInNavigation', false);
    }
}