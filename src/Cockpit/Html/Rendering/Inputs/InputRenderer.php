<?php


namespace MezzoLabs\Mezzo\Cockpit\Html\Rendering\Inputs;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\FormBuilder;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\HtmlRules;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\BooleanInput;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\SimpleInput;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\TextArea;
use MezzoLabs\Mezzo\Core\Schema\ValidationRules\Rules;

class InputRenderer
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var InputType
     */
    protected $inputType;

    /**
     * @var Collection
     */
    protected $settings;

    /**
     * @var Rules
     */
    protected $rules;

    public function __construct($name, InputType $inputType, $settings = [])
    {
        $this->name = $name;
        $this->inputType = $inputType;
        $this->settings = new Collection($settings);
    }

    public function render()
    {
        if ($this->inputType instanceof BooleanInput) {
            return $this->renderCheckbox();
        }

        if ($this->inputType instanceof TextArea)
            return $this->renderTextArea();

        if ($this->inputType instanceof SimpleInput)
            return $this->renderSimpleInput();

        return "Input type is not supported!!!";
    }

    protected function renderCheckbox()
    {
        $checkbox = $this->formBuilder()->checkbox(
            $this->name,
            1,
            $this->value(),
            $this->htmlAttributes()
        );

        $fallbackValue = '<input type="hidden" name="'. $this->name .'" value="0" />';

        return '<div class="checkbox"><label>' . $fallbackValue . $checkbox . ' ' . $this->title() . '</label></div>';
    }

    protected function renderTextArea()
    {
        $attributes = $this->addCssClass($this->htmlAttributes(['rows' => 5]), 'form-control');

        return $this->formBuilder()->textarea(
            $this->name,
            $this->value(),
            $attributes);
    }

    protected function renderSimpleInput()
    {
        $attributes = $this->addCssClass($this->htmlAttributes(), 'form-control');

        return $this->formBuilder()->input(
            $this->inputType->htmlType(),
            $this->name,
            $this->value(),
            $attributes);
    }

    protected function value()
    {
        if ($this->hasOld())
            return $this->old();

        if ($this->settings->has('value'))
            return $this->settings->get('value');

        if ($this->settings->has('default'))
            return $this->settings->get('default');

        return false;
    }


    protected function old()
    {
        $sessionName = StringHelper::fromArrayToDotNotation($this->name);

        return \Session::getOldInput($sessionName);
    }

    protected function hasOld()
    {
        $sessionName = StringHelper::fromArrayToDotNotation($this->name);

        if (\Session::hasOldInput($sessionName))
            return true;

        return false;
    }

    /**
     * @return FormBuilder
     */
    protected function formBuilder()
    {
        return app()->make(FormBuilder::class);
    }

    protected function htmlAttributes($mergeAttributes = [])
    {
        $htmlRules = (new HtmlRules($this->rules(), $this->inputType))->attributes()->toArray();

        return array_merge($htmlRules, $this->inputType->htmlAttributes(), $mergeAttributes);
    }

    protected function addCssClass(array $data, $cssClassesString)
    {
        $cssClasses = explode(' ', $cssClassesString);
        $oldCssClasses = explode(' ', $data['class'] ?? '');

        $newClassString = implode(' ', array_filter(array_merge($oldCssClasses, $cssClasses)));
        return array_merge($data, ['class' => $newClassString]);
    }

    protected function title()
    {
        return $this->settings->get('title', ucfirst($this->name));
    }

    /**
     * @return Rules
     */
    public function rules()
    {
        if (!$this->rules)
            $this->rules = $this->makeRules();

        return $this->rules;
    }

    protected function makeRules()
    {
        $rulesString = $this->settings->get('rules', '');

        return Rules::makeCollection($rulesString);
    }


}