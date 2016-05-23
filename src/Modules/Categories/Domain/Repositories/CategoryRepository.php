<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories;


use App\Category;
use App\Category as AppCategory;
use App\CategoryGroup as AppCategoryGroup;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryException;

class CategoryRepository extends ModelRepository
{
    const RELATIVES_DESCENDANTS = 1;
    const RELATIVES_ANCESTORS = 2;
    const RELATIVES_ALL = 3;

    /**
     * @param $groupIdentifier
     * @param $label
     * @param null $parentIdentifier
     * @return Category
     * @throws CannotFindCategoryException
     * @throws \MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryGroupException
     */
    public function createInGroup($groupIdentifier, $label, $parentIdentifier = null)
    {
        $group = $this->groupRepository()->findByIdentifierOrFail($groupIdentifier);

        $parent = ($parentIdentifier) ?
            $this->findByIdentifierOrFail($parentIdentifier, $group)
            : null;

        $data = [
            'label' => $label
        ];

        return $this->createCategory($data, $group, $parent);
    }

    /**
     * @return CategoryGroupRepository
     */
    protected function groupRepository()
    {
        return new CategoryGroupRepository();
    }

    /**
     * @param $category
     * @param null $group
     * @return Category
     * @throws CannotFindCategoryException
     * @throws InvalidArgumentException
     */
    public function findByIdentifierOrFail($category, $group = null)
    {
        $found = static::findByIdentifier($category, $group);

        if (!$found)
            throw new CannotFindCategoryException($category);

        return $found;
    }

    /**
     * @param $category
     * @param null $group
     * @return AppCategory|null
     * @throws InvalidArgumentException
     */
    public function findByIdentifier($category, $group = null)
    {

        if ($category instanceof Category)
            return $category;

        if (is_numeric($category))
            return $this->find($category);

        if (is_string($category) && $group) {
            return $this->findByGroupAndSlug($group, $category);
        }

        throw new InvalidArgumentException($category);
    }

    /**
     * @param $groupIdentifier
     * @param $categorySlug
     * @return AppCategory|null|static
     * @throws InvalidArgumentException
     */
    public function findByGroupAndSlug($groupIdentifier, $categorySlug)
    {
        $group = $this->groupRepository()->findByIdentifier($groupIdentifier);

        if (!$group) return null;

        $result = $this->query()->where('category_group_id', '=', $group->id)
            ->where('slug', '=', str_slug($categorySlug))->first();

        return $result;
    }

    public function findByGroup($groupIdentifier)
    {
        $group = $this->groupRepository()->findByIdentifier($groupIdentifier);

        if (!$group) return null;

        return $group->categories()->get();
    }

    /**
     * @param array $data
     * @param AppCategoryGroup $group
     * @param AppCategory $parent
     * @return AppCategory
     * @throws CannotFindCategoryException
     *
     */
    public function createCategory(array $data, AppCategoryGroup $group, AppCategory $parent = null)
    {
        $category = new AppCategory();

        $data['category_group_id'] = $group->id;

        unset($data['parent_id']);

        $category->fill($data);

        if ($parent) {
            $category->appendTo($parent);
        } else
            $category->makeRoot();

        $category->save();

        return $category;
    }

    public function create(array $data)
    {
        $group = $this->groupRepository()->findByIdentifierOrFail($data['category_group_id']);

        $parent = ($data['parent_id']) ?
            $this->findByIdentifierOrFail($data['parent_id'], $group)
            : null;

        return $this->createCategory($data, $group, $parent);
    }

    /**
     * @return AppCategory
     */
    protected function modelInstance()
    {
        return parent::modelInstance();
    }

    public function findAllAncestorIds($categories) : array
    {
        return $this->findAllRelativesIds($categories, static::RELATIVES_ANCESTORS);

    }

    public function findAllDescendantIds($categories) : array
    {
        return $this->findAllRelativesIds($categories, static::RELATIVES_DESCENDANTS);
    }

    public function findAllRelativesIds($categories, $mode = CategoryRepository::RELATIVES_ALL)
    {
        if (!is_array($categories)) {
            $categories = [$categories];
        }

        $ancestorsIds = new Collection();
        foreach ($categories as $category) {
            //Do not search in the chain if one member of the relatives chain is already in the array
            if ($ancestorsIds->search($category)) {
                continue;
            }

            //Search the relatives chain upwards
            if ($mode == static::RELATIVES_ANCESTORS || $mode == static::RELATIVES_ALL) {
                $ancestorsIds = $ancestorsIds->merge(Category::ancestorsOf($category, ['id'])->pluck('id'));
            }

            //Search the relatives chain downwards
            if ($mode == static::RELATIVES_DESCENDANTS || $mode == static::RELATIVES_ALL) {
                $ancestorsIds = $ancestorsIds->merge(Category::descendantsOf($category, ['id'])->pluck('id'));
            }
        }

        return $ancestorsIds->toArray();
    }

    /**
     * Remove the category with all the child nodes without destroying the tree
     *
     * @param $ids
     * @return int
     */
    public function delete($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as $id) {
            $category = $this->findOrFail($id);
            $category->delete();
        }

        return 1;
    }


}