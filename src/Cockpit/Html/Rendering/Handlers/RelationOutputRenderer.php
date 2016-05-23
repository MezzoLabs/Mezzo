<?php


namespace Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers;

use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;

class RelationOutputRenderer extends RelationAttributeMultipleRenderer
{

    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof \MezzoLabs\Mezzo\Core\Schema\InputTypes\ReadOnly\RelationInputMultiple;
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