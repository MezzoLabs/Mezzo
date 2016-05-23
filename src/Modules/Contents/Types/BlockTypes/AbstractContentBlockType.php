<?php


namespace MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes;


use Illuminate\Support\Facades\Lang;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\FormBuilder;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentBlockTypeContract;
use MezzoLabs\Mezzo\Modules\Contents\Contracts\ContentFieldTypeContract;
use MezzoLabs\Mezzo\Modules\Contents\Exceptions\ContentBlockException;
use MezzoLabs\Mezzo\Modules\Contents\Exceptions\NoKeyForContentBlockException;
use MezzoLabs\Mezzo\Modules\Contents\Html\BlockFormHelper;
use MezzoLabs\Mezzo\Modules\Contents\Types\FieldTypes\ContentFieldTypeCollection;

abstract class AbstractContentBlockType implements ContentBlockTypeContract
{
    protected $title = "";

    /**
     * @var ContentFieldTypeCollection;
     */
    protected $fields;

    private $formName = "";

    protected $isBooted;

    protected $options = [
        'icon' => 'ion-cube'
    ];

    public function __construct()
    {
        $this->boot();
    }

    /**
     * Returns the unique key of this content block.
     * @return string
     * @throws NoKeyForContentBlockException
     */
    public function key()
    {
        return get_class($this);
    }

    /**
     * Returns the title that will be displayed in the dashboard.
     *
     * @return string
     */
    public function title()
    {
        if (Lang::has('mezzo.modules.contents.blocks.' . $this->shortKey()))
            return Lang::get('mezzo.modules.contents.blocks.' . $this->shortKey());

        if (empty($this->title))
            return space_case(class_basename($this));

        return $this->title;
    }

    public function shortKey()
    {
        return snake_case(class_basename($this), '_');
    }

    /**
     * Adds a field to the schema of the content block.
     *
     * @param ContentFieldTypeContract $fieldType
     * @throws ContentBlockException
     */
    public function addField(ContentFieldTypeContract $fieldType)
    {
        if ($this->fields()->has($fieldType->name()))
            throw new ContentBlockException('A field with the name "' . $fieldType->name() . '" does already exist ' .
                'in "' . $this->key() . '".');

        $this->fields()->put($fieldType->name(), $fieldType);
    }


    /**
     * Returns the field types that are present in this block.
     *
     * @return ContentFieldTypeCollection
     */
    public function fields()
    {
        if (!$this->fields)
            $this->fields = new ContentFieldTypeCollection();

        return $this->fields;
    }

    /**
     * Creates a view with some variables filled in.
     *
     * @param $viewKey
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View
     */
    protected function makeView($viewKey, $mergeData = [])
    {
        return view()->make($viewKey, [
            'block' => $this,
            'fields' => $this->fields(),
            'formBuilder' => app(FormBuilder::class)
        ], $mergeData);
    }

    /**
     * @return string
     */
    public function propertyInputName($propertyName)
    {
        return "content.blocks." . $this->formName() . "." . $propertyName;
    }

    /**
     * The name attribute that represents a content field in the form array.
     *
     * @param $fieldName
     * @return string
     */
    public function inputName($fieldName)
    {
        return "content.blocks." . $this->formName() . ".fields." . $fieldName;
    }

    /**
     * The name attribute that represents a content field in the form array.
     *
     * @param $optionName
     * @return string
     */
    public function optionInputName($optionName)
    {
        return "content.blocks." . $this->formName() . ".options." . $optionName;
    }

    private function formName()
    {
        if (empty($this->formName))
            $this->formName = '{{ block.nameInForm }}';

        return $this->formName;
    }

    /**
     * Returns the rules of all fields
     *
     * @return array
     */
    public function fieldsRules()
    {
        $rules = [];

        $this->fields()->each(function (ContentFieldTypeContract $field) use (&$rules) {
            $rules[$field->name()] = $field->rulesString();
        });

        return $rules;
    }

    /**
     * A hash value of the key that is easier to handle in URLs.
     *
     * @return string
     */
    public function hash()
    {
        return md5($this->key());
    }

    /**
     * Returns the title that will be displayed in the dashboard.
     *
     * @return string
     */
    public function icon()
    {
        return $this->options['icon'];
    }

    public function form()
    {
        return new BlockFormHelper($this, app(FormBuilder::class));
    }
}