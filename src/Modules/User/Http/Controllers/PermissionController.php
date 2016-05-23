<?php

namespace MezzoLabs\Mezzo\Modules\User\Http\Controllers;


use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\CreateCategoryPage;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\CreatePermissionPage;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\EditPermissionPage;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\IndexCategoryPage;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\IndexPermissionPage;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\ShowCategoryPage;
use MezzoLabs\Mezzo\Modules\Permission\Http\Pages\Permission\ShowPermissionPage;

class PermissionController extends CockpitResourceController
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->page(IndexPermissionPage::class);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResourceRequest $request
     * @return ModuleResponse
     */
    public function create(CreateResourceRequest $request)
    {
        return $this->page(CreatePermissionPage::class);
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
        return $this->page(ShowPermissionPage::class);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    public function edit(EditResourceRequest $request, $id)
    {
        return $this->page(EditPermissionPage::class);
    }
}