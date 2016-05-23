<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Http\Transformers;


use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\Transformer;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentBlockTypeContract;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentFieldTypeContract;

class ContentBlockTypeTransformer extends Transformer
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
    ];

    protected $defaultIncludes = [
    ];

    public function transform($blockType)
    {
        if (!$blockType instanceof ContentBlockTypeContract)
            throw new InvalidArgumentException($blockType);


        $html = (\Input::get('html') != 'raw') ?
            route('cockpit::contents.block-type.html', ['hash' => $blockType->hash()]) :
            $blockType->inputsView()->render();

        $fields = $blockType->fields();
        $fieldsArray = [];
        $fields->each(function (ContentFieldTypeContract $contentField) use (&$fieldsArray) {
            $fieldsArray[$contentField->name()] = [
                'title' => $contentField->title(),
                'type' => get_class($contentField),
                'inputType' => $contentField->inputType()->name()
            ];
        });

        return [
            'key' => $blockType->key(),
            'hash' => $blockType->hash(),
            'title' => $blockType->title(),
            'fields' => $fieldsArray,
            'html' => $html
        ];
    }

}