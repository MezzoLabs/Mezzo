<?php

namespace MezzoLabs\Mezzo\Core\Fluent;


use Illuminate\Support\Fluent;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AtomicAttribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\Columns\JoinColumn;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;
use MezzoLabs\Mezzo\Core\Schema\Relations\Relation;
use MezzoLabs\Mezzo\Core\Schema\Relations\RelationSide;

class FluentAttribute extends Fluent
{

    /**
     * @return $this
     */
    public function isAtomic()
    {
        $this->offsetSet('type', 'atomic');
        return $this;
    }

    /**
     * @param Relation $relation
     * @return $this
     */
    public function relation(Relation $relation)
    {
        $this->offsetSet('relation', $relation);
        $this->isRelation();
        return $this;
    }

    public function isRelation()
    {
        $this->offsetSet('type', 'relation');
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->offsetSet('table', $table);
        return $this;
    }

    /**
     * @param string $rulesString
     * @return $this
     */
    public function rules($rulesString)
    {
        return $this->setOption('rules', $rulesString);
    }

    public function setOption($key, $value)
    {
        $options = $this->get('options', []);

        $options[$key] = $value;
        $this->offsetSet('options', $options);
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function type($type)
    {
        $this->offsetSet('type', $type);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name($name)
    {
        $this->offsetSet('name', $name);
        return $this;
    }

    /**
     * @param bool $isPersisted
     * @return $this
     */
    public function persisted($isPersisted)
    {
        $this->offsetSet('persisted', $isPersisted);
        return $this;
    }

    public function joinColumn(JoinColumn $column)
    {
        $this->offsetSet('name', $column->name());
        $this->offsetSet('relation', $column->relation());
        $this->offsetSet('table', $column->table());
        $this->isRelation();

        return $this;
    }

    /**
     * Create a real attribute from this fluent helper
     *
     * @return AtomicAttribute|RelationAttribute
     */
    public function make()
    {
        if ($this->offsetGet('type') === 'relation')
            $attribute = $this->makeRelationAttribute();
        else
            $attribute = $this->makeAtomicAttribute();

        $attribute->setTable($this->get('table', ''));
        $attribute->setPersisted($this->get('persisted', true));

        return $attribute;
    }

    /**
     * @return RelationAttribute
     */
    protected function makeRelationAttribute()
    {
        $attribute = new RelationAttribute(
            $this->offsetGet('name'),
            new RelationSide($this->offsetGet('relation'), ($this->offsetGet('table')),
                $this->get('options', [])
            ));

        return $attribute;

    }

    /**
     * @return AtomicAttribute
     */
    protected function makeAtomicAttribute()
    {
        $name = $this->offsetGet('name');
        $type = $this->get('type', 'string');

        $attribute = new AtomicAttribute(
            $name,
            InputType::fromColumnType($type, $name),
            $this->get('options', [])
        );

        return $attribute;
    }


} 