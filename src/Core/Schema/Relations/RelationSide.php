<?php


namespace MezzoLabs\Mezzo\Core\Schema\Relations;


use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;

class RelationSide
{
    /**
     * @var Relation
     */
    protected $relation;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var boolean
     */
    protected $hasOneChild;

    /**
     * Creates a new relation side. Can tell you if this side of the relation has one or many "children"
     *
     * @param Relation $relation
     * @param string $table
     */
    public function __construct(Relation $relation, $table)
    {

        $this->relation = $relation;
        $this->table = $table;

        $this->hasOneChild = $this->hasOneChild();
    }

    /**
     * @return bool
     */
    public function hasOneChild()
    {
        if ($this->hasOneChild === null) {
            $this->hasOneChild = $this->getType() === "single";
        }

        return $this->hasOneChild;
    }

    /**
     * Check if this relation side has one or many "children".
     *
     * @return string
     */
    protected function getType()
    {
        if ($this->relation instanceof ManyToMany)
            return "multiple";

        if ($this->relation instanceof OneToOne)
            return "single";

        if ($this->relation instanceof OneToMany) {
            /**
             * If the connecting table is on our side we only have one child.
             */
            if ($this->relation->joinTable() === $this->table)
                return "single";
            else
                return "multiple";
        }

        return "multiple";
    }

    /**
     * Checks if this side has the connecting column.
     *
     * @return bool
     */
    public function containsTheJoinColumn()
    {
        if ($this->relation instanceof ManyToMany)
            return true;

        if ($this->hasMultipleChildren())
            return true;

        if ($this->relation instanceof OneToOneOrMany)
            return $this->relation->joinTable() === $this->table;
    }


    /**
     * @return bool
     */
    public function hasMultipleChildren()
    {
        return !$this->hasOneChild();
    }

    /**
     * Get the primary key for this side of the relation
     *
     * @return string
     */
    public function primaryKey()
    {
        if ($this->sideType() == 'from')
            return $this->relation()->fromPrimaryKey();

        return $this->relation()->toPrimaryKey();
    }

    protected function sideType()
    {
        if ($this->relation()->fromTable() == $this->table) return 'from';

        return 'to';
    }

    /**
     * @return Relation
     */
    public function relation()
    {
        return $this->relation;
    }

    /**
     * Returns the function name of the current side.
     * This name will be represented in the model.
     *
     * @return string
     */
    public function naming()
    {
        if ($this->sideType() == 'to')
            return $this->relation()->toNaming();

        return $this->relation()->fromNaming();
    }

    /**
     * @return string
     */
    public function attributeName()
    {
        if ($this->isManyToMany() || $this->hasMultipleChildren())
            return $this->naming();

        return $this->relation()->joinColumn();
    }

    /**
     * Get the model reflection for the model in this side of the relation.
     *
     * @return ModelReflection
     */
    public function modelReflection()
    {
        $table = $this->table();
        return mezzo()->makeReflectionManager()->modelReflection($table);
    }

    /**
     * @return string
     */
    public function table()
    {
        return $this->table;
    }

    /**
     * Get the model reflection for the model on the other side of the relation.
     *
     * @return MezzoModelReflection
     */
    public function otherModelReflection()
    {
        $otherTable = $this->otherSide()->table();
        return mezzo()->makeReflectionManager()->mezzoReflection($otherTable);
    }

    /**
     * Get the other side of the relation
     *
     * @return RelationSide
     */
    public function otherSide()
    {
        if ($this->sideType() === 'from')
            $table = $this->relation()->toTable();
        else
            $table = $this->relation()->fromTable();

        return new RelationSide($this->relation(), $table);
    }

    public function isManyToMany()
    {
        return $this->relation()->isManyToMany();
    }

    public function isOneToMany()
    {
        return $this->relation()->isOneToMany() && $this->hasMultipleChildren();
    }

    public function isManyToOne()
    {
        return $this->relation()->isOneToMany() && $this->hasOneChild();
    }

    public function isOneToOne()
    {
        return $this->relation()->isOneToOne();
    }

    public function isOneToOneOrMany()
    {
        return $this->relation()->isOneToOneOrMany();
    }
} 