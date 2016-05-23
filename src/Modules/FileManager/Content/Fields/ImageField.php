<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Content\Fields;


use MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes\AbstractContentFieldType;
use MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes\ImageInput;

class ImageField extends AbstractContentFieldType
{
    protected $inputType = ImageInput::class;

    protected $rulesString = "required|exists:image_files,id";
}