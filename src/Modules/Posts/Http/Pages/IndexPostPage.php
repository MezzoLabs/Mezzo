<?php


namespace MezzoLabs\Mezzo\Modules\Posts\Http\Posts;

use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;

class IndexPostPage extends IndexResourcePage
{
    protected $view = 'modules.posts::posts.index';

    public $filtersView = 'modules.posts::partials.post_index_filters';

    public function boot()
    {
        $this->frontendOptions['backendPagination'] = true;
    }

    /**
     * @param array $merge
     * @return Collection
     */
    public function defaultIncludes($merge = [])
    {
        $merge[] = 'mainImage';

        return parent::defaultIncludes($merge);
    }


}