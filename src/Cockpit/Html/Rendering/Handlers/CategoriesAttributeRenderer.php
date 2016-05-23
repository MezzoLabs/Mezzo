<?php


namespace Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers;

use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Modules\Categories\Schema\InputTypes\CategoriesInput;

class CategoriesAttributeRenderer extends RelationAttributeMultipleRenderer
{
    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof CategoriesInput;
    }

    /**
     * Render the attribute to HTML.
     *
     * @param array $options
     * @return string
     */
    public function render(array $options = [])
    {
        $collection = $this->attribute()->query()->get();

        return view('cockpit::partials.categories_input', [
            'categories' => $collection->toTree(),
            'renderer' => $this
        ]);
    }


}