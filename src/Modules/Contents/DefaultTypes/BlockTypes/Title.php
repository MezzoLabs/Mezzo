<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\BlockTypes;


use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\TextField;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\AbstractContentBlockType;

class Title extends AbstractContentBlockType
{

    protected $options = [
        'icon' => 'ion-social-tumblr'
    ];

    /**
     * Called when a content block type is booted.
     * Now is the time to add some field types to this type of content block.
     */
    public function boot()
    {
        $this->addField(new TextField('title'));
    }

    /**
     * Create the evaluated view contents for this block.
     *
     * @return string
     */
    public function inputsView()
    {
        return $this->makeView('modules.contents::blocks.title');
    }


}