<?php


namespace MezzoLabs\Mezzo\Core\Schema\Converters\Annotations;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Annotations\Reader\RelationAnnotations;
use MezzoLabs\Mezzo\Core\Annotations\Relations\PivotColumn;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes;
use MezzoLabs\Mezzo\Core\Schema\Attributes\PivotAttribute;
use MezzoLabs\Mezzo\Core\Schema\Converters\Converter;
use MezzoLabs\Mezzo\Core\Schema\Relations\ManyToMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\OneToOneOrMany;
use MezzoLabs\Mezzo\Core\Schema\Relations\Relation;

class RelationAnnotationsConverter extends Converter
{
    /**
     * @param mixed $toConvert
     * @return Relation
     */
    public function run($toConvert)
    {
        return $this->fromAnnotationsToRelation($toConvert);
    }

    /**
     * @param RelationAnnotations $relationAnnotations
     * @return Relation
     */
    protected function fromAnnotationsToRelation(RelationAnnotations $relationAnnotations)
    {

        if ($relationAnnotations->isOneToOneOrMany())
            return $this->makeOneToOneOrMany($relationAnnotations);

        return $this->makeManyToMany($relationAnnotations);
    }

    /**
     * @param RelationAnnotations $relationAnnotations
     * @return OneToOneOrMany
     */
    protected function makeOneToOneOrMany(RelationAnnotations $relationAnnotations)
    {
        $relation = $this->makeRelationBase($relationAnnotations);

        $joinColumnAnnotation = $relationAnnotations->joinColumn();

        $relation->connectVia($joinColumnAnnotation->column, $joinColumnAnnotation->table);

        return $relation;
    }

    /**
     * Create a new relation instance with the values that are the same across all relations.
     *
     * @return OneToOneOrMany|ManyToMany
     */
    protected function makeRelationBase(RelationAnnotations $relationAnnotations)
    {
        $relation = Relation::makeByType($relationAnnotations->relationClass());

        $from = $relationAnnotations->from();
        $to = $relationAnnotations->to();

        $relation->from($from->table, $from->naming);
        $relation->to($to->table, $to->naming);

        $relation->setScopes($relationAnnotations->scopes());

        return $relation;
    }

    /**
     * @param RelationAnnotations $relationAnnotations
     * @return ManyToMany|OneToOneOrMany
     */
    protected function makeManyToMany(RelationAnnotations $relationAnnotations)
    {
        $relation = $this->makeRelationBase($relationAnnotations);

        $pivotTableAnnotation = $relationAnnotations->pivotTable();
        $pivotColumnAnnotations = $relationAnnotations->pivotColumns();

        $pivotAttributes = $this->makePivotAttributes($pivotColumnAnnotations, $relation);

        $relation->setPivot(
            $pivotTableAnnotation->name,
            $pivotTableAnnotation->fromColumn,
            $pivotTableAnnotation->toColumn,
            $pivotAttributes);

        return $relation;
    }

    /**
     * @param Collection $pivotColumns
     * @param ManyToMany $relation
     * @return Attributes
     */
    protected function makePivotAttributes(Collection $pivotColumns, ManyToMany $relation)
    {
        $attributes = new Attributes();

        $pivotColumns->each(function (PivotColumn $column) use ($attributes, $relation) {
            $attribute = new PivotAttribute($column->name, $relation, [
                'rules' => $column->rules,
                'type' => $column->type
            ]);

            $attributes->addAttribute($attribute);
        });

        return $attributes;
    }
}