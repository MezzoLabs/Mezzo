<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Content\Fields;


use MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes\AbstractContentFieldType;
use MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes\ImagesInput;

class ImagesField extends AbstractContentFieldType
{
    protected $inputType = ImagesInput::class;

    protected $rulesString = "required";
}