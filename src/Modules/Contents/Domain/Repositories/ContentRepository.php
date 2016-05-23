<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Domain\Repositories;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;

class ContentRepository extends ModelRepository
{
    /**
     * @param array $contentData
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreateWithBlocks(array $contentData)
    {
        $contentData = new Collection($contentData);
        $blocksData = $contentData->get('blocks', []);

        $exists = $contentData->get('id', null);

        if (!$exists)
            $content = parent::create($contentData->except('blocks')->toArray());
        else {
            $content = $this->findOrFail($contentData['id']);
        }

        foreach ($blocksData as &$blockData) {
            if(!$blockData){
                continue;
            }

            $blockData['content_id'] = $content->id;
            $this->blockRepository()->updateOrCreateWithArray($blockData);
        }

        $this->updateRecentText($content->id);

        return $content;
    }

    public function updateRecentText($content_id)
    {
        $content = $this->findOrFail($content_id);

        $content->recent_text = strip_tags($content->text());

        $content->save();

        return $content;
    }


    /**
     * Get an instance of the repository that handles the content blocks.
     *
     * @return ContentBlockRepository
     */
    protected function blockRepository()
    {
        return app()->make(ContentBlockRepository::class);
    }
}