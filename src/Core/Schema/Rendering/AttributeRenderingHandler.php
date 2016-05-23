<?php


namespace MezzoLabs\Mezzo\Core\Schema\Rendering;


use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\FormBuilder;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;

abstract class AttributeRenderingHandler
{
    /**
     * @var AttributeRenderEngine
     */
    protected $attributeRenderer;

    /**
     * @var Attribute|RelationAttribute
     */
    protected $attribute;

    /**
     * @var AttributeRenderingOptions
     */
    protected $options;

    /**
     * @param Attribute $attribute
     * @param AttributeRenderEngine $attributeRenderer
     */
    public function __construct(Attribute $attribute, AttributeRenderEngine $attributeRenderer)
    {
        $this->attributeRenderer = $attributeRenderer;
        $this->attribute = $attribute;
    }

    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    abstract public function handles(InputType $inputType);

    /**
     * Render the attribute to HTML.
     *
     * @return string
     */
    abstract public function render();

    /**
     * @return \Collective\Html\FormBuilder|FormBuilder
     */
    public function formBuilder()
    {
        return $this->attributeRenderer->formBuilder();
    }

    /**
     * Create a list for a select box.
     *
     * @param bool $addPleaseSelect
     * @return array
     * @throws AttributeRenderingException
     */
    public function makeEloquentList($addPleaseSelect = true)
    {
        if (!$this->attribute() instanceof RelationAttribute)
            throw new AttributeRenderingException('Cannot get a list for a non relation attribute.');

        $collection = $this->attribute()->query()->get();

        $array = $collection->pluck('label', 'id')->toArray();

        if (!$addPleaseSelect)
            return $array;

        $array[null] = 'Please Select';

        return $array;
    }

    /**
     * @return Attribute|RelationAttribute
     */
    public function attribute()
    {
        return $this->attribute;
    }

    public function relationSide()
    {
        if (!$this->attribute()->isRelationAttribute())
            throw new AttributeRenderingException('Cannot get the relation side of an atomic attribute: "' . $this->name() . '""');

        return $this->attribute()->relationSide();
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\ModelSchema
     */
    public function model()
    {
        return $this->attribute()->getModel();
    }

    private function findName()
    {
        if (!$this->getOptions()->isNested()) {
            if ($this->getOptions()->has('nameSurround')) {
                return str_replace('*', $this->attribute()->name(), $this->getOptions()->get('nameSurround', ''));
            }

            return $this->getOptions()->get('namePrefix', '') . $this->attribute()->name();
        }

        if ($this->getOptions()->parent()->relationSide()->hasOneChild())
            return $this->getOptions()->parentName() . '.' . $this->attribute()->name();

        return $this->getOptions()->parentName() . '.' . $this->getOptions()->index() . '.' . $this->attribute()->name();
    }

    /**
     * @return string
     */
    public function name()
    {
        $name = $this->findName();


        if ($this->getOptions()->nameMode() == AttributeRenderingOptions::NAME_MODE_BRACKETS) {
            $parts = explode('.', $name);


            $name = $parts[0];
            for ($i = 1; $i < count($parts); $i++) {
                $name .= '[' . $parts[$i] . ']';
            }


            return $name;

        }

        return $name;
    }

    public function ngModel()
    {
        if (!$this->getOptions()->isNested() || $this->getOptions()->parent()->relationSide()->hasOneChild())
            return 'vm.inputs["' . $this->attribute()->name() . '"]';

        $index = '$index';

        return "vm.inputs['" . $this->getOptions()->parentName() . ".'+ $index +'." . $this->attribute()->name() . "']";
    }

    public function dotNotationName()
    {
        return StringHelper::fromArrayToDotNotation($this->name());
    }

    /**
     * @param null $default
     * @return mixed|null
     */
    public function value($default = null)
    {
        $dotNotationName = $this->dotNotationName();
        $nameParts = explode('.', $dotNotationName);


        if ($this->getOptions()->has('value'))
            return $this->getOptions()->get('value');

        if ($this->getOptions()->hasAttribute('value'))
            return $this->getOptions()->getAttribute('value');


        if ($this->old() !== false)
            return $this->old();

        // Check if there is a default attribute in the options that overwrites
        if ($this->getOptions()->hasAttribute('default'))
            return $this->getOptions()->getAttribute('default');

        // Check if the name is an attribute in the model that is set in the current form
        if ($this->formBuilder()->hasModel() && data_get($this->formBuilder()->getModel(), $this->dotNotationName()) !== null) {
            return data_get($this->formBuilder()->getModel(), $this->dotNotationName());
        }

        // Check if this form is a nested relation
        if ($this->formBuilder()->hasModel() && count($nameParts) == 2) {
            $relationName = $nameParts[0];
            $nestedAttribute = $nameParts[1];

            $relation = $this->formBuilder()->getModel()->$relationName;

            if ($relation) {
                return $relation->$nestedAttribute;
            }
        }

        if ($this->getOptions()->has('default')) {
            return $this->getOptions()->get('default');
        }

        return $default;
    }

    public function valueOfAttribute($name, $default = null)
    {
        if ($this->old($name) !== false)
            return $this->old($name);

        if ($this->formBuilder()->hasModel()) {
            return data_get($this->formBuilder()->getModel(), $name, $default);
        }

        return $default;

    }

    /**
     * @param string $attributeName
     * @return mixed
     */
    public function old($attributeName = "")
    {
        if (empty($attributeName))
            $attributeName = $this->dotNotationName();

        return old($attributeName, false);
    }

    /**
     * @return array
     */
    public function htmlAttributes()
    {
        $htmlAttributes = $this->attributeRenderer->htmlAttributes($this->attribute());

        if ($this->getOptions()->required() === false) {
            unset($htmlAttributes['required']);
        }

        $optionsAttributes = $this->getOptions()->attributes();

        if ($this->getOptions()->ngModel()) {
            $optionsAttributes['ng-model'] = $this->ngModel();
        }


        return array_merge($htmlAttributes, $optionsAttributes);
    }

    /**
     * @return AttributeRenderingOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = new AttributeRenderingOptions(new Collection($options));
    }

    /**
     * Get the string of a element that is nested in this form
     *
     * @param string $nestedAttributeName
     * @param array $options
     * @return mixed
     * @throws AttributeRenderingException
     * @throws \MezzoLabs\Mezzo\Exceptions\ReflectionException
     */
    public function renderNested($nestedAttributeName, array $options = [])
    {
        $options['parent'] = $this;

        $nestedModel = $this->relationSide()->otherModelReflection()->schema();

        if (!$nestedModel->hasAttribute($nestedAttributeName))
            return "!! NESTED ATTRIBUTE NOT FOUND";

        $nestedAttribute = $nestedModel->attributes($nestedAttributeName);

        return mezzo()->attribute($nestedModel->className(), $nestedAttribute->name())->render($options);
    }

    public function renderCheckbox($id)
    {
        $checkedBoxes = $this->value([]);

        if ($checkedBoxes instanceof EloquentCollection)
            $checkedBoxes = $checkedBoxes->pluck('id')->toArray();

        //The old value can be in the id => "1"
        $checked = isset($checkedBoxes[$id]) && $this->value([])[$id] == "1";

        //...or in the 0 => id, 1 => id [..] form
        if (!$checked)
            $checked = in_array($id, $checkedBoxes);


        return $this->formBuilder()->checkbox($this->name() . '[' . $id . ']', $id, $checked);
    }


    public function before() : string
    {
        if (!$this->options->renderBefore()) return "";

        return $this->attributeRenderer->defaultBefore($this);

    }


    public function after() : string
    {
        if (!$this->options->renderAfter()) return "";

        return $this->attributeRenderer->defaultAfter($this);
    }

    public function hasError() : bool
    {
        $sessionName = StringHelper::fromArrayToDotNotation($this->name());
        return (Session::has('errors') && Session::get('errors')->has($sessionName));
    }

    public function getError() : array
    {
        $sessionName = StringHelper::fromArrayToDotNotation($this->name());

        if (!$this->hasError())
            return [];

        return Session::get('errors')->get($sessionName);
    }

    public function getErrorString() : string
    {
        return implode(' ', $this->getError());
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view($view = null, $data = [], $mergeData = [])
    {
        if (!isset($data['renderer'])) $data['renderer'] = $this;

        return view($view, $data, $mergeData);
    }

    /**
     * Returns the model instance that was set in Form::model
     *
     * @return MezzoModel|null
     */
    protected function modelInstance()
    {
        if (!$this->formBuilder()->hasModel())
            return null;

        return $this->formBuilder()->getModel();
    }


}