<?php


namespace MezzoLabs\Mezzo\Modules\User\Domain\Models;


use App\Mezzo\Generated\ModelParents\MezzoPermission;
use App\Role as AppRole;

abstract class Permission extends MezzoPermission
{
    public function roles()
    {
        return $this->belongsToMany(AppRole::class);
    }

    /**
     * @param $key
     * @return bool
     */
    public function equals($key)
    {
        $key = strtolower($key);

        return $key === $this->key();
    }

    public function key()
    {
        $name = strtolower($this->name);

        if (!empty($this->model))
            return strtolower($this->model) . '.' . $name;

        return $name;
    }
}