<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Reader;


use MezzoLabs\Mezzo\Core\Annotations\Annotation;
use MezzoLabs\Mezzo\Core\Annotations\Relations\From;
use MezzoLabs\Mezzo\Core\Annotations\Relations\JoinColumn as JoinColumnAnnotation;
use MezzoLabs\Mezzo\Core\Annotations\Relations\PivotColumn;
use MezzoLabs\Mezzo\Core\Annotations\Relations\PivotTable;
use MezzoLabs\Mezzo\Core\Annotations\Relations\RelationAnnotation;
use MezzoLabs\Mezzo\Core\Annotations\Relations\Scopes as ScopesAnnotation;
use MezzoLabs\Mezzo\Core\Annotations\Relations\To;
use MezzoLabs\Mezzo\Core\Schema\Converters\Annotations\RelationAnnotationsConverter;
use MezzoLabs\Mezzo\Core\Schema\Relations\ManyToMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToOne;
use MezzoLabs\Mezzo\Core\Schema\Relations\Relation;
use MezzoLabs\Mezzo\Core\Schema\Relations\Scopes;
use MezzoLabs\Mezzo\Exceptions\AnnotationException;

class RelationAnnotations extends PropertyAnnotations
{

    /**
     * @var string
     */
    protected $relationClass;

    /**
     * @var Relation
     */
    protected $relation;

    /**
     * @return From
     */
    public function from()
    {
        return $this->get('From');
    }

    /**
     * @return To
     */
    public function to()
    {
        return $this->get('To');
    }

    /**
     * @return ScopesAnnotation
     */
    public function scopesAnnotation()
    {
        return $this->get('Scopes');
    }

    /**
     * Returns the JoinColumn annotation. Only available for one to one or many relations.
     *
     * @return JoinColumnAnnotation
     * @throws AnnotationException
     */
    public function joinColumn()
    {
        return $this->get('JoinColumn');
    }

    /**
     * @return PivotTable
     * @throws AnnotationException
     */
    public function pivotTable()
    {
        return $this->get('PivotTable');
    }

    /**
     * @return array
     */
    public function pivotColumns()
    {
        return $this->annotations()->collection()->filter(function (Annotation $annotation) {
            return $annotation->isType(PivotColumn::class);
        });
    }

    /**
     * @return bool
     */
    public function isManyToMany()
    {
        return $this->relationClass() === ManyToMany::class;
    }

    /**
     * @return string
     * @throws AnnotationException
     */
    public function relationClass()
    {
        if (!$this->relationClass) {

            if ($this->has('OneToOne'))
                $this->relationClass = OneToOne::class;
            else if ($this->has('OneToMany'))
                $this->relationClass = OneToMany::class;
            else if ($this->has('ManyToMany'))
                $this->relationClass = ManyToMany::class;
            else
                throw new AnnotationException('No valid relation type given.');
        }

        return $this->relationClass;
    }

    /**
     * @return RelationAnnotation
     * @throws AnnotationException
     */
    public function mainAnnotation()
    {
        foreach (['OneToOne', 'OneToMany', 'ManyToMany'] as $possibleType) {
            if ($this->has($possibleType))
                return $this->get($possibleType);
        }

        throw new AnnotationException('Relation annotation does not have a valid type.');
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\Relations\Relation
     */
    public function relation()
    {
        if (!$this->relation)
            $this->relation = $this->schemaConverter()->run($this);

        return $this->relation;
    }

    /**
     * @return RelationAnnotationsConverter
     */
    protected function schemaConverter()
    {
        return new RelationAnnotationsConverter();
    }

    public function scopes()
    {
        return Scopes::makeFromString($this->scopesString());
    }

    public function scopesString()
    {
        if ($this->has('scopes')) {
            return $this->scopesAnnotation()->value;
        }

        return "";
    }

    /**
     * Checks if the given annotations list is correct.
     * @return bool
     * @throws AnnotationException
     */
    protected function validate()
    {
        if (!$this->has('from')) {
            throw new AnnotationException('A relation needs to have a "from" annotation: ' . $this->name);
        }

        if (!$this->has('to')) {
            throw new AnnotationException('A relation needs to have a "to" annotation: ' . $this->name);
        }

        if ($this->isOneToOneOrMany())
            return $this->validateOneToOneOrMany();

        return $this->validateManyToMany();
    }

    public function isOneToOneOrMany()
    {
        return $this->isOneToOne() || $this->isOneToMany();
    }

    public function isOneToOne()
    {
        return $this->relationClass() === OneToOne::class;
    }

    public function isOneToMany()
    {
        return $this->relationClass() === OneToMany::class;
    }

    /**
     * @return bool
     * @throws AnnotationException
     */
    protected function validateOneToOneOrMany()
    {
        if (!$this->has('JoinColumn'))
            throw new AnnotationException('A one to one or many relation needs to have ' .
                'a "JoinColumn" annotation: ' . $this->name);

        return true;
    }

    /**
     * @return bool
     * @throws AnnotationException
     */
    protected function validateManyToMany()
    {
        if (!$this->has('PivotTable'))
            throw new AnnotationException('A many to many relation needs to have ' .
                'a "PivotTable" annotation: ' . $this->name);

        return true;
    }


}