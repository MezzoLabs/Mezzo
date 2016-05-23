<?php


namespace MezzoLabs\Mezzo\Modules\User\Domain\Models;


use App\Mezzo\Generated\ModelParents\MezzoRole;
use App\Permission as AppPermission;
use App\User as AppUser;
use MezzoLabs\Mezzo\Modules\User\Domain\Repositories\RoleRepository;

abstract class Role extends MezzoRole
{
    public function users()
    {
        return $this->belongsToMany(AppUser::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(AppPermission::class);
    }

    public function givePermissions($permissions)
    {
        return $this->repository()->givePermissionsTo($this, $permissions);
    }

    public static function repository()
    {
        return app()->make(RoleRepository::class);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasPermission($key)
    {
        if ($key instanceof \App\Permission)
            $key = $key->key();

        $hasPermission = false;

        $this->permissions->each(function (Permission $permission) use ($key, &$hasPermission) {
            if ($permission->equals($key)) {
                $hasPermission = true;
                return false;
            }
        });

        return $hasPermission;
    }
}