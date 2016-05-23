<?php


namespace MezzoLabs\Mezzo\Modules\Role\Http\Pages\Role;

use MezzoLabs\Mezzo\Cockpit\Pages\Resources\CreateResourcePage;

class CreateRolePage extends CreateResourcePage
{
    public function boot()
    {
        $this->options('visibleInNavigation', true);
    }
}