<?php


namespace MezzoLabs\Mezzo\Modules\General\Domain\Repositories;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;

class OptionRepository extends ModelRepository
{
    /**
     * @param array $columns
     * @return Collection
     */
    public function all($columns = array('*'), QueryObject $query = null)
    {
        return parent::all($columns)->sortBy('name');
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return MezzoModel
     * @throws RepositoryException
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return parent::update($data, $id, $attribute);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        $data['name'] = strtolower($data['name']);
        return parent::create($data);
    }


}