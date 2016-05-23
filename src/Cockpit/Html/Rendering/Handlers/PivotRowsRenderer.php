<?php


namespace Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers;

use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\PivotRowsInput;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingHandler;

class PivotRowsRenderer extends AttributeRenderingHandler
{

    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof PivotRowsInput;
    }

    /**
     * Render the attribute to HTML.
     *
     * @param array $options
     * @return string
     * @throws \MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingException
     */
    public function render(array $options = [])
    {
        return $this->view('cockpit::rendering.pivot_rows_input')->render();

    }

    public function before() : string
    {
        return "<label>" . $this->attribute()->title() . "</label>";

    }

    public function after() : string
    {
        return "";

    }


}