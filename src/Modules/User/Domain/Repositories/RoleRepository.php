<?php


namespace MezzoLabs\Mezzo\Modules\User\Domain\Repositories;


use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;

class RoleRepository extends ModelRepository
{
    /**
     * @return \App\Role
     */
    public function findOrCreateAdmin()
    {
        $found = $this->findByName('admin');

        if (!$found)
            return $this->create(['name' => 'admin', 'label' => 'Administrator']);

        return $found;
    }

    /**
     * Find a role by its name or create a new one.
     *
     * @param string $name
     * @param string|null $label
     * @return \App\Role
     * @throws \MezzoLabs\Mezzo\Exceptions\RepositoryException
     */
    public function findOrCreate(string $name, string $label = null) : \App\Role
    {
        $found = $this->findByName($name);

        if ($found) {
            return $found;
        }

        if (empty($label))
            $label = space_case($name);

        return $this->create(['name' => $name, 'label' => $label]);
    }

    /**
     * @param $name
     * @return \App\Role|null
     */
    public function findByName($name)
    {
        return $this->findBy('name', strtolower($name));
    }

    public function givePermissionsTo(\App\Role $role, $permissions)
    {
        if ($permissions instanceof EloquentCollection)
            $permissions = $this->pluckIds($permissions)->toArray();

        $role->permissions()->sync($permissions, false);
    }

    /**
     * @param \App\Role $role
     * @param $permissions
     * @return array
     */
    public function syncPermissions(\App\Role $role, $permissions)
    {
        if ($permissions instanceof EloquentCollection)
            $permissions = $this->pluckIds($permissions)->toArray();

        return $role->permissions()->sync($permissions);
    }


    /**
     * @param $roleId
     * @return \App\Role
     */
    public function normalizeRole($roleId)
    {
        if ($roleId instanceof \App\Role)
            return $roleId;

        if (is_integer($roleId))
            return $this->findOrFail($roleId);

        return $this->findByOrFail('name', $roleId);
    }


}