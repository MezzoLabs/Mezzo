<?php


namespace MezzoLabs\Mezzo\Modules\Role\Http\Pages\Role;

use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;

class IndexRolePage extends IndexResourcePage
{
    protected $view = 'modules.user::pages.role.index';

    protected $options = [
        'visibleInNavigation' => true,
        'appendToUri' => '',
        'renderedByFrontend' => false,
        'permissions' => 'administrate'
    ];


}