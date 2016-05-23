<?php


namespace MezzoLabs\Mezzo\Modules\Posts\Schema\Content\Blocks;


use Illuminate\View\View;
use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\SingleRelationField;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\AbstractContentBlockType;

class PostBlock extends AbstractContentBlockType
{
    protected $options = [
        'icon' => 'ion-ios-paper'
    ];

    /**
     * Called when a content block type is booted.
     * Now is the time to add some field types to this type of content block.
     */
    public function boot()
    {
        $relation = new SingleRelationField('post');
        $relation->related = "Post";

        $this->addField($relation);
    }

    /**
     * Create the evaluated view contents for this block.
     *
     * @return View
     */
    public function inputsView()
    {
        return $this->makeView('modules.posts::content_blocks.post');
    }
}