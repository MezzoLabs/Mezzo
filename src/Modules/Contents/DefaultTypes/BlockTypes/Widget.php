<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\BlockTypes;


use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\RichTextField;
use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\TextField;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\AbstractContentBlockType;

class Widget extends AbstractContentBlockType
{
    protected $options = [
        'icon' => 'fa fa-square-o'
    ];

    /**
     * Called when a content block type is booted.
     * Now is the time to add some field types to this type of content block.
     */
    public function boot()
    {
        $this->addField(new TextField('key'));
    }

    /**
     * Create the evaluated view contents for this block.
     *
     * @return string
     */
    public function inputsView()
    {
        return $this->makeView('modules.contents::blocks.widget');
    }


}