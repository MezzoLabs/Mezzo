<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories;


use App\CategoryGroup as AppCategoryGroup;
use App\CategoryGroup;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryGroupException;

class CategoryGroupRepository extends ModelRepository
{

    /**
     * @param $category
     * @return AppCategoryGroup
     * @throws CannotFindCategoryGroupException
     * @throws InvalidArgumentException
     */
    public function findByIdentifierOrFail($category)
    {
        $found = $this->findByIdentifier($category);

        if (!$found)
            throw new CannotFindCategoryGroupException($category);

        return $found;
    }

    /**
     * @param $group
     * @return AppCategoryGroup|null
     * @throws InvalidArgumentException
     */
    public function findByIdentifier($group)
    {
        if ($group instanceof CategoryGroup)
            return $group;

        return \Cache::remember('catgory_group.identifier.' . $group, 5, function () use ($group) {
            if (is_numeric($group))
                return $this->find($group);

            if (is_string($group)) {
                return $this->findBySlug($group);
            }

            throw new InvalidArgumentException($group);
        });
    }


    /**
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public
    function findBySlug($slug)
    {
        return $this->modelInstance()->findBySlug($slug);
    }

    /**
     * @return AppCategoryGroup
     */
    protected
    function modelInstance()
    {
        return parent::modelInstance();
    }




}