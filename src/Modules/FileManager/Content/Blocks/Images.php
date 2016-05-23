<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Content\Blocks;


use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\AbstractContentBlockType;
use MezzoLabs\Mezzo\Modules\FileManager\Content\Fields\ImagesField;

class Images extends AbstractContentBlockType
{

    protected $options = [
        'icon' => 'ion-images'
    ];

    /**
     * Called when a content block type is booted.
     * Now is the time to add some field types to this type of content block.
     */
    public function boot()
    {
        $this->addField(new ImagesField('images'));
    }

    /**
     * Create the evaluated view contents for this block.
     *
     * @return string
     */
    public function inputsView()
    {
        return $this->makeView('modules.file-manager::content_blocks.images');

    }


}