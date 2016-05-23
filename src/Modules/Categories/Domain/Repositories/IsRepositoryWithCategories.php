<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories;

use Illuminate\Database\Eloquent\Builder;


trait IsRepositoryWithCategories
{
    public function addInCategoriesScope(Builder $q1, array $categories)
    {
        $categoryRepository = app(CategoryRepository::class);

        $ancestors = $categoryRepository->findAllDescendantIds($categories);

        $categories = array_merge($categories, $ancestors);

        return $q1->whereHas('categories', function (Builder $q2) use ($categories) {
            return $q2->whereIn('id', $categories);
        });

    }
}