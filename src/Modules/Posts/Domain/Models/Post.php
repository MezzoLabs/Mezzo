<?php


namespace MezzoLabs\Mezzo\Modules\Posts\Domain\Models;


use App\Mezzo\Generated\ModelParents\MezzoPost;
use App\User;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Builder;


abstract class Post extends MezzoPost implements SluggableInterface
{
    use SluggableTrait;

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