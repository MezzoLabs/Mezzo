<?php


namespace Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers;

use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputSingle;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingHandler;

class RelationAttributeSingleRenderer extends AttributeRenderingHandler
{

    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof RelationInputSingle;
    }

    /**
     * Render the attribute to HTML.
     *
     * @return string
     */
    public function render()
    {
        return $this->formBuilder()->relationship($this->attribute);
    }


}