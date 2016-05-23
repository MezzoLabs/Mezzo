<?php

namespace MezzoLabs\Mezzo\Modules\User\Http\Controllers;


use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\User\Http\Pages\User\CreateUserPage;
use MezzoLabs\Mezzo\Modules\User\Http\Pages\User\EditUserAddressesPage;
use MezzoLabs\Mezzo\Modules\User\Http\Pages\User\EditUserPage;
use MezzoLabs\Mezzo\Modules\User\Http\Pages\User\IndexUserPage;
use MezzoLabs\Mezzo\Modules\User\Http\Pages\User\ShowUserPage;
use MezzoLabs\Mezzo\Modules\User\Http\Pages\User\UserSubscriptionsPage;

class UserController extends CockpitResourceController
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->page(IndexUserPage::class);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResourceRequest $request
     * @return ModuleResponse
     */
    public function create(CreateResourceRequest $request)
    {
        return $this->page(CreateUserPage::class);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    public function show(ShowResourceRequest $request, $id)
    {
        return $this->page(ShowUserPage::class);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    public function edit(EditResourceRequest $request, $id = 0)
    {
        return $this->page(EditUserPage::class);
    }

    public function editAddresses(EditResourceRequest $request, $id = 0)
    {
        return $this->page(EditUserAddressesPage::class);
    }


}