<?php


namespace MezzoLabs\Mezzo\Modules\Posts\Domain\Models;


use App\Mezzo\Generated\ModelParents\MezzoPost;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable as SluggableTrait;
use Illuminate\Database\Eloquent\Builder;
use MezzoLabs\Mezzo\Core\ThirdParties\Sluggable\DefaultSluggableTrait;


abstract class Post extends MezzoPost
{
    use SluggableTrait, DefaultSluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function scopeIsPublished(Builder $q, bool $isPublished = true)
    {

        if (!$isPublished) {
            return $q->where(function (Builder $q) {
                $q->where('state', '!=', 'published');
                $q->orWhere('published_at', '>', Carbon::now());
            });

        }

        return $q->where('state', '=', 'published')->where('published_at', '<=', Carbon::now());

    }

    public function scopeHasText(Builder $q, string $text)
    {
        return $this->repository()->addHasTextScope($q, $text);

    }

    public function scopeInCategories(Builder $q, array $categories)
    {
        return $this->repository()->addInCategoriesScope($q, $categories);
    }


}