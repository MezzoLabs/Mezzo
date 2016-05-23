<?php


namespace Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers;

use MezzoLabs\Mezzo\Core\Schema\InputTypes\CheckboxInput;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;

class CheckboxAttributeRenderer extends RelationAttributeMultipleRenderer
{
    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof CheckboxInput;
    }

    /**
     * Render the attribute to HTML.
     *
     * @param array $options
     * @return string
     */
    public function render(array $options = [])
    {
        $htmlAttributes = $this->htmlAttributes();
        $htmlAttributes['class'] = 'checkbox';

        return $this->formBuilder()->checkbox($this->name(), 1, $this->value(), $htmlAttributes);
    }


}