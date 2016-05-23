<?php

namespace MezzoLabs\Mezzo\Modules\Contents\Contracts;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;

interface ContentFieldTypeContract
{

    /**
     * A name that is unique for the parent content block.
     *
     * @return string
     */
    public function name();

    /**
     * The title that will be displayed in the dashboard.
     *
     * @return string
     */
    public function title();

    /**
     * Returns the input type of this content field.
     *
     * @return InputType
     */
    public function inputType();

    /**
     * Returns a collection of options that will determine the look and rules of this
     * Content field type.
     *
     * @return Collection
     */
    public function options();

    /**
     * @return string
     */
    public function rulesString();

    /**
     * Check if this field has to be filled out before a block can be saved.
     *
     * @return boolean
     */
    public function isRequired();

    /**
     * Returns the html attributes that are specific for this fiels.
     *
     * @return array
     */
    public function htmlAttributes(): array;

}