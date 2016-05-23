<?php


namespace MezzoLabs\Mezzo\Modules\Addresses\Schema\Rendering;


use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingHandler;
use MezzoLabs\Mezzo\Modules\Addresses\Schema\InputTypes\AddressInput;

class AddressAttributeRenderer extends AttributeRenderingHandler
{

    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof AddressInput;
    }

    /**
     * Render the attribute to HTML.
     *
     * @return string
     */
    public function render()
    {
        return view('modules.addresses::address_nested_form', ['renderer' => $this]);
    }

    public function before() : string
    {
        return "";
    }

    public function after() : string
    {
        return "";
    }
}