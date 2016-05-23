<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\BlockTypes;


use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\UrlField;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\AbstractContentBlockType;

class WebVideo extends AbstractContentBlockType
{

    protected $options = [
        'icon' => 'ion-social-youtube'
    ];

    /**
     * Called when a content block type is booted.
     * Now is the time to add some field types to this type of content block.
     */
    public function boot()
    {
        $this->addField(new UrlField('url'));
    }

    /**
     * Create the evaluated view contents for this block.
     *
     * @return string
     */
    public function inputsView()
    {
        return $this->makeView('modules.contents::blocks.web_video');
    }
}