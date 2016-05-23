<?php


namespace MezzoLabs\Mezzo\Http\Transformers\Plugins;


use Illuminate\Support\Facades\Auth;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;

class PermissionsTransformerPlugin extends TransformerPlugin
{
    /**
     * @var PermissionGuard
     */
    private $guard;

    public function __construct(PermissionGuard $guard)
    {

        $this->guard = $guard;
    }

    /**
     * @param MezzoModel $model
     * @return array
     */
    public function transform(MezzoModel $model) : array
    {

        if (!Auth::check()) {
            return [];
        }

        return ['_permissions' => [
            'edit' => $this->guard->allowsEdit($model, null, ['log' => false]),
            'delete' => $this->guard->allowsDelete($model, null, ['log' => false]),
            'show' => $this->guard->allowsShow($model, null, ['log' => false]),
        ]];
    }
}