<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Helpers\Translator;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\TextInput;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentFieldTypeContract;
use MezzoLabs\Mezzo\Modules\Contents\Exceptions\ContentFieldException;

abstract class AbstractContentFieldType implements ContentFieldTypeContract
{

    /**
     * @var string
     */
    protected $inputType = TextInput::class;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * @var InputType
     */
    private $inputTypeInstance;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    protected $rulesString = "";

    /**
     * Create a new content field that will be visible inside a content block.
     *
     * @param $name
     * @param array $options
     */
    public function __construct($name, $options = [])
    {
        $this->options = new Collection($options);

        if ($this->options->has('rules'))
            $this->rulesString = $this->options->get('rules');

        $this->name = $name;
    }

    /**
     * Returns the input type of this content field.
     *
     * @return InputType
     */
    public function inputType()
    {
        if (!$this->inputTypeInstance)
            $this->inputTypeInstance = $this->makeInputType();

        return $this->inputTypeInstance;
    }

    /**
     * Create a input type instance out of the input type class.
     *
     * @return InputType
     * @throws ContentFieldException
     */
    private function makeInputType()
    {
        if (empty($this->inputType))
            throw new ContentFieldException('No input type set for ' . get_class($this));

        $inputType = app()->make($this->inputType);


        if (!$inputType instanceof InputType)
            throw new ContentFieldException('Input type for "' . get_class($this) . '" is not valid.');

        return $inputType;
    }

    /**
     * Returns a collection of options that will determine the look and rules of this
     * Content field type.
     *
     * @return Collection
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * Check if this field has to be filled out before a block can be saved.
     *
     * @return boolean
     */
    public function isRequired()
    {
        $rules = explode('|', $this->rulesString);
        return in_array('required', $rules);
    }

    /**
     * A name that is unique for the parent content block.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    public function title()
    {
        $translation = Translator::find([
            'validation.attributes.' . strtolower($this->name)
        ]);

        if($translation){
            return $translation;
        }

        return space_case($this->name);
    }

    /**
     * @return string
     */
    public function rulesString()
    {
        return $this->rulesString;
    }

    public function htmlAttributes() : array
    {
        return [];
    }

    /**
     * @param string $rulesString
     */
    public function setRulesString($rulesString)
    {
        $this->rulesString = $rulesString;
    }

    /**
     * @return string
     */
    public function getRulesString()
    {
        return $this->rulesString;
    }
}