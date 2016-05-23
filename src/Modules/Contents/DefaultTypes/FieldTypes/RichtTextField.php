<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\RichTextArea;
use MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes\AbstractContentFieldType;

class RichTextField extends AbstractContentFieldType
{
    protected $inputType = RichTextArea::class;

    protected $rulesString = "required|between:2,50000";

}