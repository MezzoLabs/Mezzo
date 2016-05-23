<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Http\Requests;


use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use Mezzolabs\Mezzo\Modules\Contents\Http\FormObjects\ContentBlocksFormObject;

/**
 * Class IsRequestWithContentBlocks
 * @package MezzoLabs\Mezzo\Modules\Contents\Http\Requests
 *
 * @method array all
 * @method MezzoModelReflection modelReflection
 */
trait IsRequestWithContentBlocks
{

    protected function makeContentBlocksFormObject()
    {
        return new ContentBlocksFormObject($this->modelReflection(), $this->all());
    }

}