<?php

namespace MezzoLabs\Mezzo\Modules\User\Http\Controllers;


use Illuminate\Http\Request;
use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\Role\Http\Pages\Role\CreateRolePage;
use MezzoLabs\Mezzo\Modules\Role\Http\Pages\Role\EditRolePage;
use MezzoLabs\Mezzo\Modules\Role\Http\Pages\Role\IndexRolePage;
use MezzoLabs\Mezzo\Modules\Role\Http\Pages\Role\ShowRolePage;
use MezzoLabs\Mezzo\Modules\User\Domain\Repositories\PermissionRepository;
use MezzoLabs\Mezzo\Modules\User\Domain\Repositories\RoleRepository;

class RoleController extends CockpitResourceController
{


    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        $role = $this->roleFromInput($request);

        return $this->page(IndexRolePage::class, [
            'roles' => $this->repository()->all(),
            'role' => $role,
            'user' => \Auth::user(),
            'permissions' => $this->permissionRepository()->all()
        ]);
    }

    protected function roleFromInput(Request $request)
    {
        $role = $request->get('role', 'admin');
        return $this->repository()->findByOrFail('name', $role);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResourceRequest $request
     * @return ModuleResponse
     */
    public function create(CreateResourceRequest $request)
    {
        return $this->page(CreateRolePage::class);
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
        return $this->page(ShowRolePage::class);
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
        return $this->page(EditRolePage::class);
    }

    /**
     * @return PermissionRepository
     */
    protected function permissionRepository()
    {
        return app()->make(PermissionRepository::class);
    }

    public function update(UpdateResourceRequest $request, $id)
    {
        $role = $this->repository()->findOrFail($id);

        $this->repository()->syncPermissions($role, array_keys($request->get('permissions', [])));

        return $this->redirectToPage(IndexRolePage::class, ['role' => $role->name])->with('message', 'The permissions for the "' . $role->label . '" role were updated.');
    }

    /**
     * @return RoleRepository
     */
    public function repository()
    {
        return parent::repository();
    }
}