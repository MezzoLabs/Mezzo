<?php


namespace MezzoLabs\Mezzo\Cockpit\Html\Rendering;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\TextInput;
use MezzoLabs\Mezzo\Core\Schema\ValidationRules\Rules;

class HtmlRules
{

    /**
     * @var Rules
     */
    protected $rules;

    protected $inputType;

    public function __construct(Rules $rules, InputType $inputType = null)
    {
        $this->rules = $rules;
        $this->inputType = ($inputType) ? $inputType : new TextInput();
    }

    /**
     * @return Collection
     */
    public function attributes()
    {
        $attributes = new Collection();

        if ($this->maxLength() !== null) {
            $attributes->put($this->maxLengthAttributeName(), $this->maxLength());
        }

        if ($this->minLength() !== null) {
            $attributes->put($this->minLengthAttributeName(), $this->minLength());
        }

        if ($this->rules()->isRequired())
            $attributes->put('required', 'required');

        $attributes->put('data-validation-rules', $this->rules()->toString());

        return $attributes;
    }

    private function maxLengthAttributeName()
    {
        return ($this->inputType()->isNumeric()) ? 'max' : 'data-max';
    }

    private function minLengthAttributeName()
    {
        return ($this->inputType()->isNumeric()) ? 'min' : 'data-min';
    }

    /**
     * How many characters are allowed for this attribute.
     *
     * @return int|null
     * @throws \MezzoLabs\Mezzo\Exceptions\MezzoException
     */
    protected function maxLength()
    {
        if ($this->rules()->has('max'))
            return $this->rules()->get('max')->parameters(0);

        if ($this->rules()->has('between'))
            return $this->rules()->get('between')->parameters(1);

        return null;
    }

    /**
     * @return Rules
     */
    protected function rules()
    {
        return $this->rules;
    }

    /**
     * How many characters this attribute should have.
     *
     * @return int|null
     * @throws \MezzoLabs\Mezzo\Exceptions\MezzoException
     */
    protected function minLength()
    {
        if ($this->rules()->has('min'))
            return $this->rules()->get('min')->parameters(0);

        if ($this->rules()->has('between'))
            return $this->rules()->get('between')->parameters(0);

        return null;
    }

    /**
     * @return InputType
     */
    public function inputType()
    {
        return $this->inputType;
    }
}