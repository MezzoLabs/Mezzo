<?php


namespace MezzoLabs\Mezzo\Modules\Pages\Domain\Models;


use App\ContentField;
use App\Mezzo\Generated\ModelParents\MezzoPage;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

abstract class Page extends MezzoPage implements SluggableInterface
{

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function findBlock($handle)
    {
        return $this->content->findBlock($handle);
    }

    /**
     * @param $blockHandle
     * @param $fieldName
     * @return ContentField
     * @throws \MezzoLabs\Mezzo\Modules\Contents\Exceptions\ContentBlockException
     */
    public function field($blockHandle, $fieldName)
    {
        $block = $this->content->findBlock($blockHandle);

        if (!$block) {
            return null;
        }

        return $block->getField($fieldName);
    }

    public function fieldValue($blockHandle, $fieldName)
    {
        $field = $this->field($blockHandle, $fieldName);


        return ($field) ? $field->value : null;
    }

    public function textValue($blockHandle, $default = "")
    {
        $value = $this->fieldValue($blockHandle, 'text');

        return ($value) ? $value : $default;
    }

    public function titleValue($blockHandle, $default = "")
    {
        $value = $this->fieldValue($blockHandle, 'title');

        return ($value) ? $value : $default;
    }
}