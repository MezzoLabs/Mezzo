<?php

namespace MezzoLabs\Mezzo\Core\Schema\Attributes;


use ArrayAccess;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Helpers\Translator;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToOneOrMany;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderEngine;
use MezzoLabs\Mezzo\Core\Schema\ValidationRules\Rules;

class Attribute
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var InputType
     */
    protected $type;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var ModelSchema
     */
    protected $model;

    /**
     * @var bool
     */
    protected $persisted = true;

    /**
     * @var Collection
     */
    protected $rules;

    /**
     * @var string
     */
    protected $title;

    /**
     * Get the html attributes as array.
     *
     * @return array
     */
    public function htmlAttributes()
    {
        $attributes = [
            'type' => $this->type->htmlType(),
            'name' => $this->name,
        ];

        return array_filter($attributes);
    }

    /**
     * @return bool
     */
    public function hasTable()
    {
        return !empty($this->getTable());
    }

    /**
     * @return string
     */
    public function getTable()
    {
        if (!empty($this->table))
            return $this->table;

        if ($this->model)
            return $this->model->tableName();

        return "";
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return boolean
     */
    public function isPersisted()
    {
        return $this->persisted;
    }

    /**
     * @param boolean $persisted
     */
    public function setPersisted($persisted)
    {
        $this->persisted = $persisted;
    }

    /**
     * @return ModelSchema
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param ModelSchema $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return boolean
     */
    public function hasModel()
    {
        return !empty($this->model);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return InputType
     */
    public function type()
    {
        return $this->type;
    }

    public function isAtomic()
    {
        return $this instanceof AtomicAttribute;
    }

    public function isForeignKey()
    {
        if (!($this instanceof RelationAttribute)) return false;

        $relation = $this->relation();

        if ($relation instanceof OneToOneOrMany)
            return $relation->joinColumn() == $this->name();
        else
            return true;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Check if this attribute has rules.
     *
     * @return bool
     */
    public function hasRules()
    {
        return $this->rules()->count() > 0;
    }

    /**
     * @return Rules
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * @return Collection
     */
    public function options()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function qualifiedName()
    {
        return $this->getTable() . '.' . $this->name();
    }

    /**
     * @return bool
     */
    public function isRelationAttribute()
    {
        return $this instanceof RelationAttribute;
    }

    public function naming()
    {
        if (!$this->isRelationAttribute())
            return $this->name();

        return $this->relationSide()->naming();
    }

    /**
     * @return string
     */
    public function title()
    {
        if (!$this->title) {
            $this->title = $this->makeTitle();
        }

        return $this->title;
    }

    protected function makeTitle() : string
    {

        $translationKeys = [];

        if($this->getModel()){
            $translationKeys[] = 'attributes.' . $this->getModel()->shortName() . '.' . $this->naming();
        }

        $translationKeys[] = 'attributes.' . $this->naming();
        $translationKeys[] = 'validation.attributes.' . $this->naming();


        $translation = Translator::find($translationKeys);

        if(is_array($translation))
            $translation = array_values($translation)[0];


        if ($translation){

            return $translation;

        }

        $nameParts = explode('_', $this->name());

        foreach ($nameParts as $i => $namePart) {
            if ($namePart == "id" && $i != 0) $namePart = "";
            $nameParts[$i] = ucfirst($namePart);
        }


        return implode(' ', $nameParts);

    }

    /**
     * Render this attribute schema as a HTML string.
     *
     * @param array $options
     * @return string
     * @throws \MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingException
     */
    public function render($options = [])
    {
        $renderEngine = AttributeRenderEngine::make();
        return $renderEngine->render($this, $options);
    }

    public function renderer($options = [])
    {
        $renderEngine = AttributeRenderEngine::make();
        return $renderEngine->findHandler($this, $options);
    }

    /**
     * Check if this attribute is visible in JSON.
     * E.g. password and the remember token should not be visible.
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->options->get('visible', false);
    }

    /**
     * Check if this attribute is mass assignable.
     *
     * @return boolean
     */
    public function isFillable()
    {
        return $this->options->get('fillable', false);
    }

    /**
     * @param array|ArrayAccess $options
     */
    protected function setOptions($options)
    {
        $this->options = new Collection($options);

        $this->rules = Rules::makeCollection($this->options->get('rules', ""));
    }

    /**
     * @param $formName
     * @return bool
     */
    public function visibleInForm($formName)
    {
        $hiddenByAnnotation = in_array($formName, $this->hiddenInForms());
        $hiddenByFillable = !$this->isFillable();

        return !$hiddenByAnnotation && !$hiddenByFillable;
    }

    /**
     * @param array $default
     * @return array
     */
    public function hiddenInForms($default = [])
    {
        return $this->options()->get('hiddenInForms', $default);
    }

    /**
     * Find out the input type based on the side of the relation we are on.
     *
     * @return RelationInputMultiple|RelationInputSingle
     */
    protected function findType()
    {
        $type = $this->options()->get('type');

        if(is_object($type)){
            return $type;
        }

        if(!class_exists($type)){
            return null;
        }

        return app()->make($type);
    }
} 