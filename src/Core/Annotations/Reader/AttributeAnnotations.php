<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Reader;


use MezzoLabs\Mezzo\Core\Annotations\Attribute as AttributeAnnotation;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\RelationInputMultiple;
use MezzoLabs\Mezzo\Core\Schema\Relations\Relation;
use MezzoLabs\Mezzo\Exceptions\AnnotationException;

class AttributeAnnotations extends PropertyAnnotations
{
    /**
     * @var InputType
     */
    protected $inputType;

    /**
     * @var Relation
     */
    protected $relation;

    public static $defaultHiddenInForms = [
        'id' => ['create', 'edit'],
        'slug' => ['create', 'edit'],
        'created_at' => ['create'],
        'updated_at' => ['create', 'update'],
        'deleted_at' => ['create', 'update'],
    ];

    /**
     * @return array
     */
    public function options()
    {
        return [
            'rules' => $this->modelReflection()->rules($this->name()),
            'visible' => !in_array($this->name(), $this->modelReflection()->instance()->getHidden()),
            'fillable' => in_array($this->name(), $this->modelReflection()->instance()->getFillable()),
            'type' => $this->inputType(),
            'hiddenInForms' => $this->hiddenInFormsArray()
        ];
    }

    /**
     * @return array
     */
    public function hiddenInFormsArray()
    {
        $hiddenString = $this->attributeAnnotation()->hidden;

        if (empty($hiddenString)) {
            return [];
        }

        return explode(',', $hiddenString);
    }

    /**
     * Create a relation annotations collection from this attribute annotation.
     *
     * @return RelationAnnotations
     */
    public function toRelationAnnotations()
    {
        return new RelationAnnotations($this->name(), $this->annotations(), $this->model());
    }

    /**
     * @return Relation|null
     * @throws AnnotationException
     */
    public function relation()
    {
        if (!$this->isRelation()) return null;

        if (!$this->relation)
            $this->relation = $this->findRelation();

        return $this->relation;
    }

    public function isRelation()
    {
        return $this->inputType()->isRelation();
    }

    /**
     * @return InputType
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     */
    public function inputType()
    {
        if (!$this->inputType)
            $this->inputType = InputType::make($this->inputTypeString());

        return $this->inputType;
    }

    /**
     * @return string
     */
    public function inputTypeString()
    {
        return $this->attributeAnnotation()->type;
    }

    /**
     * @return AttributeAnnotation
     * @throws AnnotationException
     */
    public function attributeAnnotation()
    {
        return $this->get('Attribute');
    }


    /**
     * @return Relation|null
     * @throws AnnotationException
     */
    protected function findRelation()
    {
        $relationAnnotationsCollection = $this->model()->relationAnnotations();

        $relation = null;

        $relationAnnotationsCollection->each(function (RelationAnnotations $relationAnnotations) use (&$relation) {
            if ($this->belongsToRelationAnnotations($relationAnnotations)) {
                $relation = $relationAnnotations->relation();
                return false;
            }
        });

        if (!$relation) {
            throw new AnnotationException('Cannot find a relation for attribute ' . $this->qualifiedColumn());
        }

        return $relation;
    }

    /**
     * Check if this attribute annotations belong to a certain relation annotations group.
     *
     * @param RelationAnnotations $relationAnnotations
     * @return bool
     */
    private function belongsToRelationAnnotations(RelationAnnotations $relationAnnotations)
    {

        /**
         * Check if the column of this attribute is part of the relation.
         */
        if ($relationAnnotations->relation()->columns()->has($this->qualifiedColumn())) {
            return true;
        }

        /**
         * Check if the relation and this attribute are named equally.
         * E.g: roles is a attribute with multiple inputs but also the name of the relation.
         */
        if ($this->inputType() instanceof RelationInputMultiple && $relationAnnotations->name() === $this->name()) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the given annotations list is correct.
     * @return bool
     * @throws AnnotationException
     */
    protected function validate()
    {
        if (!$this->annotations->have('Attribute')) {
            throw new AnnotationException('A attribute need to have an attribute annotation.');
        }

        return true;
    }
}