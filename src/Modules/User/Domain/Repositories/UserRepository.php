<?php


namespace MezzoLabs\Mezzo\Modules\User\Domain\Repositories;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;

class UserRepository extends ModelRepository
{
    /**
     * @param \App\User $user
     * @return Collection
     */
    public function permissions(\App\User $user)
    {
        $permissions = new Collection();

        $user->roles->each(function (\App\Role $role) use (&$permissions) {
            $permissions = $permissions->merge($role->permissions()->get());
        });

        return $permissions;
    }

    /**
     * @param \App\User $user
     * @param $role
     */
    public function attachRole(\App\User $user, $role)
    {
        $role = $this->roleRepository()->normalizeRole($role);

        $user->roles()->sync([$role->id], false);
    }

    /**
     * @param \App\User $user
     * @param $role
     */
    public function detachRole(\App\User $user, $role)
    {
        $role = $this->roleRepository()->normalizeRole($role);

        $user->roles()->detach($role->id);
    }

    protected function  roleRepository()
    {
        return app()->make(RoleRepository::class);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return \App\User
     */
    public function findByOrFail($attribute, $value, $columns = ['*'])
    {
        return parent::findByOrFail($attribute, $value, $columns);
    }

    /**
     * @param $email
     * @return \App\User
     */
    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function backend()
    {
        return $this->where('backend', '=', 1)->get();
    }
}