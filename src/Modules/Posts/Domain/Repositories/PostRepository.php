<?php


namespace MezzoLabs\Mezzo\Modules\Posts\Domain\Repositories;


use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Exceptions\RepositoryException;
use MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories\IsRepositoryWithCategories;
use packages\mezzolabs\mezzo\src\Modules\Contents\Domain\Repositories\IsRepositoryWithContentBlocks;

class PostRepository extends ModelRepository
{
    use IsRepositoryWithContentBlocks, IsRepositoryWithCategories;

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        $data = $this->replaceBlocksWithContentId($data);

        return parent::create($data->toArray());
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
        $post = $this->findOrFail($id);

        $data['content']['id'] = $post->content->id;

        if (isset($data['content'])) {
            $data = $this->replaceBlocksWithContentId($data)->toArray();
        }


        return parent::update($data, $id, $attribute);
    }

    public function addHasTextScope(EloquentBuilder $q, string $text)
    {
        $q->where(function (EloquentBuilder $q2) use ($text) {
            $q2->orWhere('title', 'LIKE', '%' . $text . '%');
            $q2->orWhere('teaser', 'LIKE', '%' . $text . '%');

            $this->addSearchContentBlockScope($q2, $text, 'or');
        });

    }

    public function moveOwnership(\App\User $fromUser, \App\User $toUser)
    {
        return DB::table('posts')
            ->where('user_id', $fromUser->id)
            ->update(array('user_id' => $toUser->id));
    }



}