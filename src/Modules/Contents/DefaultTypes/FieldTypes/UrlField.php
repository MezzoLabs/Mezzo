<?php


namespace MezzoLabs\Mezzo\Modules\Contents\DefaultTypes\FieldTypes;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\UrlInput;
use MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes\AbstractContentFieldType;

class UrlField extends AbstractContentFieldType
{
    protected $inputType = UrlInput::class;

    protected $rulesString = "url|required|between:2,255";

}