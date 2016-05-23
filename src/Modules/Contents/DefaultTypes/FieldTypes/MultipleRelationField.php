<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputMultiple;

class MultipleRelationField extends RelationField
{
    protected $inputType = RelationInputMultiple::class;

    protected $rulesString = "";

    public function htmlAttributes() : array
    {
        $attributes = parent::htmlAttributes();

        $attributes['multiple'] = true;

        return $attributes;
    }


}