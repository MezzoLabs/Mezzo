<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Domain\Repositories;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;

class ContentBlockRepository extends ModelRepository
{
    /**
     * @param array $data
     * @return Model
     */
    public function updateOrCreateWithArray(array $data)
    {
        $data = new Collection($data);
        $fieldValues = $data->get('fields', []);
        $optionsArray = $data->get('options', []);

        $attributesData = new Collection($data);
        $attributesData->forget(['fields']);
        $attributesData->put('options', json_encode($optionsArray));

        if (empty($attributesData->get('name')))
            $attributesData->put('name', str_random());

        $exists = !empty($attributesData->get('id'));

        if (!$exists)
            $block = parent::create($attributesData->toArray());
        else
            $block = parent::update($attributesData->except('id')->toArray(), $attributesData->get('id'));

        foreach ($fieldValues as $name => $value) {
            $id = null;

            if ($exists && $block->fields->where('name', $name)->count() != 0) {
                $id = $block->fields->where('name', $name)->first()->id;
            }

            $this->updateOrCreateField($block, $name, $value, $id);
        }
    }

    /**
     * @param \App\ContentBlock|MezzoModel $block
     * @param $name
     * @param $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function updateOrCreateField(\App\ContentBlock $block, $name, $value, $id = null)
    {
        // Convert ID array to comma separated string
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $data = [
            'content_block_id' => $block->id,
            'name' => $name,
            'value' => $value
        ];

        if (!$id)
            return $this->fieldRepository()->create($data);

        return $this->fieldRepository()->update($data, $id);

    }

    protected function fieldRepository()
    {
        return app()->make(ContentFieldRepository::class);
    }

    public function updateRecentText($content_id, $prepend = "")
    {

    }

}