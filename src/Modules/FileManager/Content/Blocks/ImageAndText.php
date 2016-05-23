<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Content\Blocks;


use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\RichTextField;
use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\TextField;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\AbstractContentBlockType;
use MezzoLabs\Mezzo\Modules\FileManager\Content\Fields\ImageField;

class ImageAndText extends AbstractContentBlockType
{

    protected $options = [
        'icon' => 'ion-image'
    ];
    /**
     * Called when a content block type is booted.
     * Now is the time to add some field types to this type of content block.
     */
    public function boot()
    {
        $this->addField(new RichTextField('text'));
        $this->addField(new ImageField('image'));
    }

    /**
     * Create the evaluated view contents for this block.
     *
     * @return string
     */
    public function inputsView()
    {
        return $this->makeView('modules.file-manager::content_blocks.image_and_text');

    }


}