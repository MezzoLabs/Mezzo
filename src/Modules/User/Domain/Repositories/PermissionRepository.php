<?php


namespace MezzoLabs\Mezzo\Modules\User\Domain\Repositories;


use App\Permission;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;

class PermissionRepository extends ModelRepository
{
    /**
     * @param $data
     * @return Permission
     */
    public function updateOrInsert($data)
    {
        $found = $this->findByNameAndModel($data['name'], $data['model']);

        if ($found) {
            return $this->update($data, $found->id);
        }

        return $this->create($data);
    }

    /**
     * @param $name
     * @param null $modelName
     * @return \App\Permission
     */
    public function findByNameAndModel($name, $modelName = NULL)
    {
        if ($modelName) $modelName = strtolower($modelName);

        return $this->query()
            ->where('name', '=', strtolower($name))
            ->where('model', '=', $modelName)
            ->get()->first();
    }
}