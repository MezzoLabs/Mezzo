<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Http\Transformers;


use App\ContentBlock;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;
use MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes\MultipleRelationField;

class ContentBlockTransformer extends EloquentModelTransformer
{
    /**
     * @var string
     */
    protected $modelName = ContentBlock::class;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
    ];

    protected $defaultIncludes = [
    ];

    public function transform($model)
    {
        if (!$model instanceof ContentBlock)
            throw new InvalidArgumentException($model);

        $array = parent::transform($model);

        $array['options'] = json_decode($array['options']);
        $array['fields'] = [];

        foreach ($model->fields as $field) {
            $value = $field->value;

            if ($field->getType() instanceof MultipleRelationField) {
                $value = explode(',', $value);
            }

            $array['fields'][$field->name] = $value;
        }

        return $array;
    }

}