<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Schema\Rendering;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingHandler;
use MezzoLabs\Mezzo\Modules\FileManager\Schema\InputTypes\FilesInput;

class FilesAttributeRenderer extends AttributeRenderingHandler
{
    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof FilesInput;
    }

    /**
     * Render the attribute to HTML.
     *
     * @return string
     */
    public function render()
    {
        return $this->formBuilder()->filePicker(
            $this->attribute()->name(),
            $this->attribute()->otherModelReflection()->instance(), [
                'multiple' => $this->attribute()->hasMultipleChildren(),
                'rules' => $this->attribute()->rules()
            ]
        );
    }

}