<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Helpers\ArrayAnalyser;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AttributeValue;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\Relations\ManyToMany;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\RepositoryException;

class RelationUpdater extends EloquentRepository
{
    /**
     * @var MezzoModel
     */
    protected $eloquentModel;

    /**
     * @var AttributeValue
     */
    protected $attributeValue;

    /**
     * @var BelongsTo|BelongsToMany|HasOneOrMany|HasOne|HasMany|EloquentRelation
     */
    protected $eloquentRelation;

    /**
     * @var ArrayAnalyser
     */
    protected $arrayAnalyser;

    /**
     * @param MezzoModel $model
     * @param AttributeValue $attributeValue
     */
    public function __construct(MezzoModel $model, AttributeValue $attributeValue)
    {
        $this->eloquentModel = $model;
        $this->attributeValue = $attributeValue;

        $this->eloquentRelation = $model->relation($attributeValue->name());

        $this->arrayAnalyser = app()->make(ArrayAnalyser::class);

        $this->validate();
    }

    /**
     * Check if the construct parameters are correct.
     *
     * @return bool
     * @throws RepositoryException
     */
    protected function validate()
    {
        if (!$this->attribute() instanceof RelationAttribute)
            throw new RepositoryException($this->attribute()->qualifiedName() . ' is not a relation.');

        return true;
    }

    /**
     * @return RelationAttribute
     */
    public function attribute()
    {
        return $this->attributeValue()->attribute();
    }

    /**
     * @return AttributeValue
     */
    public function attributeValue()
    {
        return $this->attributeValue;
    }

    /**
     * @return array|bool
     * @throws InvalidArgumentException
     * @throws RepositoryException
     */
    public function run()
    {
        /**
         * m:n Relation -> sync the Pivot
         */
        if ($this->relationSide()->isManyToMany())
            return $this->updateBelongsToManyRelation($this->eloquentRelation(), $this->newIds());

        /**
         * 1:n Relation (Left side) -> update the child rows in the foreign table
         */
        if ($this->relationSide()->isOneToMany() && $this->relationSide()->hasMultipleChildren())
            return $this->updateHasManyRelation($this->eloquentRelation(), $this->newIds());

        /**
         * 1:1 Relation (Side without the joining column) -> update the foreign joining column
         */
        if ($this->relationSide()->isOneToOne() && $this->relationSide()->containsTheJoinColumn())
            return $this->updateHasOneRelation($this->eloquentRelation(), $this->newId());

        throw new RepositoryException('This relation should not be updated with the relation updater. ' .
            'Since it is a simple atomic value you should update the column of the main table instead.');

    }

    public function relationSide()
    {
        return $this->attribute()->relationSide();
    }

    /**
     * Updates m:n relationships.
     *
     * @param BelongsToMany $relation
     * @param array $ids
     * @return array
     */
    protected function updateBelongsToManyRelation(BelongsToMany $relation, array $ids)
    {
        $isPivotRowsArray = $this->arrayAnalyser->isPivotRowsArray($ids);
        if ($isPivotRowsArray) {
            $ids = $this->convertPivotRowsToSyncArray($ids);
        }

        $pivotAttributes = $this->pivotAttributes();

        if (!$isPivotRowsArray && $pivotAttributes->has('sort')) {
            return $this->updateSortedBelongsToManyRelation($relation, $ids);
        }

        $result = $relation->sync($ids);
        return (is_array($result));
    }

    protected function updateSortedBelongsToManyRelation(BelongsToMany $relation, array $ids)
    {
        $relation->sync([]);

        $pivotColumns = [];
        $i = 0;
        foreach ($ids as $id) {
            $pivotColumns[$id] = ['sort' => $i];
            $i++;
        }

        $result = $relation->sync($pivotColumns);
        return is_array($result);
    }

    /**
     * @return Collection|\MezzoLabs\Mezzo\Core\Schema\Attributes\Attributes
     */
    protected function pivotAttributes()
    {
        $relation = $this->attribute()->relation();

        if (!$relation instanceof ManyToMany) {
            return new Collection();
        }

        return $relation->pivotAttributes();

    }

    /**
     * Convert a pivot rows array from an HTTP request to an eloquent sync array.
     *
     * E.g.:
     * From
     * [0 => [id = 6, pivot_amount = 2]*
     * To
     * [6 => [amount => 2]
     *
     * @param array $pivotRows
     * @return array
     */
    private function convertPivotRowsToSyncArray(array $pivotRows)
    {
        $sync = [];

        foreach ($pivotRows as $row) {
            $sync[$row['id']] = [];

            foreach ($row as $pivot_key => $value) {
                if (!Str::startsWith($pivot_key, 'pivot_')) {
                    continue;
                }

                $key = str_replace('pivot_', '', $pivot_key);

                $sync[$row['id']][$key] = $value;
            }
        }


        return $sync;
    }

    /**
     * @return BelongsTo|BelongsToMany|HasMany|HasOne|HasOneOrMany|EloquentRelation
     */
    public function eloquentRelation()
    {
        return $this->eloquentRelation;
    }

    /**
     * The id that we have to update.
     *
     * @return int
     */
    protected function newId() : int
    {
        return $this->processId($this->attributeValue()->value());
    }

    /**
     *  Ids that we have to update.
     *
     * @return array
     */
    protected function newIds() : array
    {
        $value = $this->attributeValue()->value();

        if (empty($value)) {
            return [];
        }

        if (is_string($value) && str_contains($value, ',')) {
            return $this->processIds(explode(',', $value));
        }

        if (is_string($value) && is_numeric($value))
            return $this->processIds([$value]);

        return $this->processIds($value);
    }

    /**
     * @param array $ids
     * @return mixed
     */
    protected function processIds(array $ids) : array
    {
        //check if array keys are the ids. this is the case for checkboxes
        // e.g. {15 : "on"} --> [15]
        if (!isset($ids[0]) && count($ids) > 0)
            $ids = array_keys($ids);

        //go through the numeric ids
        for ($i = 0; $i != count($ids); $i++) {
            $ids[$i] = $this->processId($ids[$i]);
        }

        return $ids;
    }

    /**
     * @param $id
     * @return int|array
     */
    protected function processId($id)
    {
        if ($this->arrayAnalyser->isPivotRowArray($id)) {
            return $id;
        }

        if (is_array($id)) {
            throw new RepositoryException('Id cannot be an array. ' . var_export($id, true));
        }

        if (!is_numeric($id)) {
            throw new RepositoryException('Cannot update a relation with a non numeric id: "' . $id . '".');
        }


        return intval($id);
    }

    /**
     * Set the parent of many child resources (Left side of a 1:n relationship)
     *
     * @param HasMany $relation
     * @param array $ids
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function updateHasManyRelation(HasMany $relation, array $ids)
    {
        $foreignKey = $relation->getPlainForeignKey();

        foreach ($ids as $id) {
            if (!is_integer($id))
                throw new InvalidArgumentException($id);

            $foreignModel = $relation->getRelated();
            $foreignChild = $foreignModel->newQuery()->where($foreignModel->getQualifiedKeyName(), '=', $id);
            $result = $foreignChild->update([$foreignKey => $this->parentId()]);

            if ($result != 1)
                return false;
        }

        return true;
    }

    public function parentId()
    {
        return $this->eloquentModel()->id;
    }

    /**
     * @return MezzoModel
     */
    public function eloquentModel()
    {
        return $this->eloquentModel;
    }

    /**
     * Update the part of a 1:1 relation that contains the joining column.
     *
     * @param HasOne $relation
     * @param $id
     * @return bool
     * @throws RepositoryException
     */
    protected function updateHasOneRelation(HasOne $relation, integer $id)
    {
        $foreignModel = $relation->getRelated();
        $foreignChild = $foreignModel->newQuery()->where($foreignModel->getQualifiedKeyName(), '=', $id);
        $foreignKey = $relation->getPlainForeignKey();
        $result = $foreignChild->update([$foreignKey => $this->parentId()]);

        return $result == 1;
    }

    public function qualifiedName()
    {
        return $this->relationSide()->relation()->qualifiedName();
    }

    /**
     * @param $eloquentRelationClass
     * @return bool
     * @throws RepositoryException
     */
    protected function relationHasToBe($eloquentRelationClass)
    {
        if (!$this->eloquentRelation() instanceof $eloquentRelationClass)
            throw new RepositoryException($this->relationName() . ' is not a ' . $eloquentRelationClass . ' relation.');

        return true;
    }

    public function relationName()
    {
        return $this->attribute()->name();
    }


}