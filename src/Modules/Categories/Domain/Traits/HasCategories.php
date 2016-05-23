<?php

namespace MezzoLabs\Mezzo\Modules\Categories\Domain\Traits;

use App\Category;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Modules\Categories\Exceptions\CategoryNotAllowedForModelException;

/**
 * Class HasCategories
 * @package MezzoLabs\Mezzo\Modules\Categories\Domain\Traits
 *
 * @property EloquentCollection $categories
 */
trait HasCategories
{
    /**
     * @param $category
     * @return bool|void
     * @throws \MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryException
     */
    public function addCategory($category)
    {
        if($this->hasCategory($category))
            return true;

        $category = Category::findByIdentifierOrFail($category);

        $this->canHaveCategoryOrFail($category);

        $this->categories()->attach($category->id);

        return true;
    }

    /**
     * @param $category
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws InvalidArgumentException
     */
    public function hasCategory($category)
    {
        if($category instanceof \App\Category)
            $category = $category->id;

        if(is_integer($category))
            return \App\Category::find($category);

        if(is_string($category))
            return \App\Category::findBySlug($category);

        throw new InvalidArgumentException($category);
    }

    /**
     * @param $category
     * @return bool
     * @throws CategoryNotAllowedForModelException
     */
    public function canHaveCategoryOrFail($category){
        $canHave = $this->canHaveCategory($category);

        if(!$canHave)
            throw new CategoryNotAllowedForModelException($category, get_class($this));

        return true;
    }

    /**
     * Check if the category is in a group that allows this model.
     *
     * @param $category
     * @return bool
     * @throws \MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryException
     */
    public function canHaveCategory($category)
    {
        $category = Category::findByIdentifierOrFail($category);
        return $category->group->hasModel(static::class);
    }

    /**
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    /**
     * @param $category
     * @return int
     * @throws \MezzoLabs\Mezzo\Modules\Categories\Exceptions\CannotFindCategoryException
     */
    public function removeCategory($category)
    {
        $category = Category::findByIdentifierOrFail($category);
        return $this->categories()->detach($category->id);
    }

    /**
     * @param $categoryIds
     * @return array
     */
    public function syncCategories($categoryIds = [])
    {
        foreach($categoryIds as $categoryId){
            $this->canHaveCategoryOrFail($categoryId);
        }

        return $this->categories()->sync($categoryIds);
    }



}