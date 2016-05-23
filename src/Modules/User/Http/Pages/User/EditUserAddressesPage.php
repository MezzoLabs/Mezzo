<?php

namespace MezzoLabs\Mezzo\Modules\User\Http\Pages\User;

use App\User;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\EditResourcePage;
use MezzoLabs\Mezzo\Modules\User\Http\Controllers\UserController;

class EditUserAddressesPage extends EditResourcePage
{
    protected $view = 'modules.user::pages.user.edit_addresses';

    protected $model = User::class;

    protected $controller = UserController::class;

    protected $action = "editAddresses";
}