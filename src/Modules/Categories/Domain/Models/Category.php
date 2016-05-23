<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Models;


use App\CategoryGroup as AppCategoryGroup;
use App\Mezzo\Generated\ModelParents\MezzoCategory;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories\CategoryGroupRepository;
use MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories\CategoryRepository;
use MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryException;

abstract class Category extends MezzoCategory implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'label',
        'save_to' => 'slug',
    ];


    /**
     * @param $category
     * @param null $group
     * @return \App\Category|null
     * @throws InvalidArgumentException
     */
    public static function findByIdentifier($category, $group = null)
    {
        return static::repository()->findByIdentifier($category, $group);
    }

    /**
     * @return CategoryRepository
     */
    public static function repository()
    {
        return new CategoryRepository();
    }

    /**
     * @param $category
     * @return \App\Category
     * @throws CannotFindCategoryException
     * @throws InvalidArgumentException
     */
    public static function findByIdentifierOrFail($category, $group = null)
    {
        return static::repository()->findByIdentifierOrFail($category, $group);
    }

    /**
     * @param $groupIdentifier
     * @param $label
     * @param null $parent
     * @return \App\Category
     * @throws \MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryGroupException
     */
    public static function createInGroup($groupIdentifier, $label, $parent = null)
    {
        return static::repository()->createInGroup($groupIdentifier, $label, $parent);
    }

    /**
     * @return CategoryGroupRepository
     */
    public static function groupRepository()
    {
        return new CategoryGroupRepository();
    }

    public function group()
    {
        return $this->belongsTo(AppCategoryGroup::class, 'category_group_id', 'id');
    }

    public function createAndAppend($label)
    {
        return $this->repository()->createCategory(
            $data = ['label' => $label], $this->group, $this
        );
    }

}