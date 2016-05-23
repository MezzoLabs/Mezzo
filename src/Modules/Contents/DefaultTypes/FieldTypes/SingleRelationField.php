<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputMultiple;

class SingleRelationField extends RelationField
{
    protected $inputType = RelationInputMultiple::class;

    protected $rulesString = "";


}