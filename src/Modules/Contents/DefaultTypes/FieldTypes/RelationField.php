<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes;


use MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes\AbstractContentFieldType;

abstract class RelationField extends AbstractContentFieldType
{
    public $related = "";

    public $scopes = "";

    public function htmlAttributes() : array
    {
        $attributes = parent::htmlAttributes();

        $attributes['data-related'] = $this->related;
        $attributes['data-scopes'] = $this->scopes;

        return $attributes;
    }


}