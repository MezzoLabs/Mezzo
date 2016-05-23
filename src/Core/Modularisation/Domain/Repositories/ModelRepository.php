<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories;


use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mezzolabs\Mezzo\Cockpit\Http\FormObjects\NestedRelations;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflectionSet;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AttributeValue;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AttributeValues;
use MezzoLabs\Mezzo\Exceptions\RepositoryException;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use MezzoLabs\Mezzo\Http\Requests\Queries\Sorting;

class ModelRepository extends EloquentRepository
{
    /**
     * @var MezzoModelReflection
     */
    protected $modelReflection;

    /**
     * The class name of the model.
     *
     * @var string
     */
    protected $model;


    /**
     * @param ModelReflection|null $modelReflection
     * @throws RepositoryException
     */
    public final function __construct(ModelReflection $modelReflection = null)
    {
        if (!$modelReflection) {
            $modelReflection = $this->guessModel();
        }
        $this->modelReflection = $modelReflection;

        if (!$this->modelReflection)
            throw new RepositoryException('Cannot find a model for repository "' . get_class($this) . '" .');

        $this->model = $this->modelReflection->className();
    }

    /**
     * @return ModelReflection
     */
    private function guessModel()
    {
        $modelName = $this->guessModelName();

        return mezzo()->model($modelName);
    }

    /**
     * Try to find a model that fits the repository class name (<ModelName>Repository.php)
     *
     * @return string
     */
    private function guessModelName()
    {
        return str_replace('Repository', '', Singleton::reflection(static::class)->getShortName());
    }

    /**
     * Create a new generic model repository for a given model class.
     *
     * @param string|ModelReflection|ModelReflectionSet $model
     * @return static
     * @throws RepositoryException
     */
    public static function makeRepository($model = null)
    {
        if ($model) {
            // Find the model reflection, normalize the $model variable.
            $model = mezzo()->model($model);

            return new ModelRepository($model);
        }

        if (static::class === ModelRepository::class)
            throw new RepositoryException('You need a model for a generic model repository.');

        return mezzo()->make(static::class);
    }

    /**
     * @return static
     * @throws RepositoryException
     */
    public static function instance()
    {
        return static::makeRepository(null);
    }

    /**
     * @param array $columns
     * @param QueryObject $query
     * @return Collection
     */
    public function all($columns = array('*'), QueryObject $query = null)
    {
        if ($query) {
            return $this->search($query, $columns);
        }

        $order = $this->order();

        return $this->query()->orderBy($order[0], $order[1])->get($columns);
    }


    public function relationshipItems(EloquentRelation $relation, $columns = array('*'), QueryObject $queryObject)
    {
        return (new EloquentQueryExecutor($queryObject, $relation->getQuery()))->run()->get($columns);
    }

    public function order()
    {
        if($this->modelReflection()->attributes()->has('created_at')){
            return ['created_at', 'desc'];
        }

        return ['id', 'desc'];
    }

    public function count(QueryObject $queryObject)
    {
        $builder = (new EloquentQueryExecutor($queryObject, $this->query()))->run();

        $query = $builder->getQuery();

        $query->offset = null;
        $query->limit = null;

        return $query->count();
    }

    public function search(QueryObject $queryObject, $columns = array('*'))
    {
        if ($queryObject->sortings()->isEmpty()) {
            $order = $this->order();
            $queryObject->sortings()->add(new Sorting($order[0], $order[1]));
        }

        return $this->query($queryObject)->get($columns);
    }


    /**
     * @param QueryObject $queryObject
     * @return EloquentBuilder
     */
    public function query(QueryObject $queryObject = null)
    {
        if (!$queryObject) {
            return $this->modelReflection->instance()->newQuery();
        }

        return (new EloquentQueryExecutor($queryObject, $this->query()))->run();
    }

    /**
     * @param QueryObject $queryObject
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(QueryObject $queryObject = null, $perPage = 15, $columns = array('*'))
    {
        return $this->query($queryObject)->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return Model
     * @throws RepositoryException
     */
    public function create(array $data)
    {
        $values = $this->values($data, true);

        $modelInstance = $this->modelInstance();

        $model = $modelInstance->create($values->inMainTableOnly()->toArray());

        if (!$model)
            throw new RepositoryException('Cannot create new model of type ' . $this->modelReflection()->className());

        $this->updateRelations($model, $values->inForeignTablesOnly());


        return $model;
    }

    public function createWithNestedRelations(array $data, NestedRelations $relations)
    {
        $nestedRelationsProcessor = new NestedRelationsProcessor($relations);
        $nestedRelationsProcessor->updateOrCreateBefore();

        // Bring the saved ids from the nested relations into the data array
        $data = array_merge($data, $nestedRelationsProcessor->ids());
        // Remove the relations data from main data array
        $data = array_diff_key($data, $relations->names());

        $model = $this->create($data);

        $nestedRelationsProcessor->updateOrCreateAfter($model->id);

        return $model;
    }


    public function updateWithNestedRelations(array $data, $id, NestedRelations $relations)
    {
        $nestedRelationsProcessor = new NestedRelationsProcessor($relations);
        $nestedRelationIds = $nestedRelationsProcessor->updateOrCreateBefore();

        // Bring the saved ids from the nested relations into the data array
        $data = array_merge($data, $nestedRelationIds);
        // Remove the relations data from main data array
        $data = array_diff_key($data, $relations->names());

        $model = $this->update($data, $id);

        $nestedRelationsProcessor->updateOrCreateAfter($model->id);

        return $model;

    }

    /**
     * Update or create a database record.
     *
     * @param array $data
     * @param array $identifyingColumns Columns that are used to check if there is already a similar row in the database.
     * @return Model
     * @throws RepositoryException
     */
    public function updateOrCreate(array $data, array $identifyingColumns = ['*'])
    {
        if (empty($identifyingColumns) || $identifyingColumns[0] === '*')
            $identifyingColumns = array_keys($data);

        $identifyingValues = [];
        foreach ($data as $key => $value)
            if (in_array($key, $identifyingColumns)) $identifyingValues[$key] = $value;

        if (empty($identifyingValues))
            throw new RepositoryException('Cannot use update or create if there are no ' .
                'columns that we can use for identifying the column.');


        $found = $this->where($identifyingValues)->first(['id']);


        if ($found) {
            return $this->update($data, $found->id);
        }


        return $this->create($data);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string $column
     * @param  string $operator
     * @param  mixed $value
     * @param  string $boolean
     * @return EloquentBuilder
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->query()->where($column, $operator, $value, $boolean);
    }

    /**
     * @param array $data
     * @return AttributeValues
     */
    protected function values(array $data, bool $strict = false)
    {
        return AttributeValues::fromArray($this->modelSchema(), $data, $strict);
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\ModelSchema
     */
    public function modelSchema()
    {
        return $this->modelReflection()->schema();
    }

    /**
     * @return MezzoModelReflection
     */
    public function modelReflection()
    {
        return $this->modelReflection;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function modelInstance()
    {
        return $this->modelReflection->instance(true);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return MezzoModel
     * @throws RepositoryException
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $values = $this->values($data, true);

        $model = $this->findByOrFail($attribute, $id);


        $result = $this->updateRow($values->inMainTableOnly(), $model);

        if (!$result)
            throw new RepositoryException("Updating the model \"" . get_class($model) . ':' . $id . "\" failed.");

        $relationResult = $this->updateRelations($model, $values->inForeignTablesOnly());

        if (!$relationResult)
            throw new RepositoryException('Relation update failed.');

        return $result;
    }


    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findByOrFail($attribute, $value, $columns = ['*'])
    {
        $found = $this->findBy($attribute, $value, $columns);

        if (!$found)
            throw new ModelNotFoundException();

        return $found;
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return Model
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->query()->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param AttributeValues $atomicAttributes
     * @param MezzoModel $model
     * @return MezzoModel
     */
    protected function updateRow(AttributeValues $atomicAttributes, MezzoModel $model)
    {
        $values = $atomicAttributes->toArray();

        if (empty($values))
            return $model;

        $model->fill($values);


        if (count($model->getDirty()) == 0) {
            return $model;
        }


        $saved = $model->save();

        return $model;
    }

    /**
     * @param MezzoModel $model
     * @param AttributeValues $relationAttributes
     * @return bool
     */
    protected function updateRelations(MezzoModel $model, AttributeValues $relationAttributes)
    {
        $relationAttributes->each(function (AttributeValue $attributeValue) use ($model) {
            $result = $this->updateRelation($model, $attributeValue);
            if (!$result)
                throw new RepositoryException('Cannot update the relation ' . $attributeValue->name());
        });

        return true;
    }

    /**
     * @param MezzoModel $model
     * @param AttributeValue $attributeValue
     * @return array
     * @throws RepositoryException
     */
    protected function updateRelation(MezzoModel $model, AttributeValue $attributeValue)
    {
        $relationUpdater = new RelationUpdater($model, $attributeValue);
        return $relationUpdater->run();
    }

    /**
     * @param $ids
     * @return int
     */
    public function delete($ids)
    {
        return $this->modelInstance()->destroy($ids);
    }

    /**
     * @param $id
     * @param array $columns
     * @param array $with
     * @return MezzoModel
     */
    public function find($id, $columns = array('*'), $with = [])
    {
        return $this->query()->where('id', '=', $id)->with($with)->first($columns);
    }


    /**
     * @param $id
     * @param array $columns
     * @param array $with
     * @return Collection|MezzoModel
     */
    public function findOrFail($id, $columns = array('*'), $with = [])
    {
        $found = $this->find($id, $columns, $with);

        if (!$found)
            throw (new ModelNotFoundException)->setModel(get_class($this->modelInstance()));

        return $found;
    }

    public function findByUrlIdentifier($identifier, $columns = array('*'), $with = [])
    {
        if ($this->modelReflection()->attributes()->has('slug')) {
            return $this->findByOrFail('slug', $identifier, $columns);
        }

        return $this->findOrFail($identifier, $columns, $with);
    }

    public function exists($id, $table = null)
    {
        if (!$table) $table = $this->tableName();

        return parent::exists($id, $table);
    }

    /**
     * @param $relation
     * @return BelongsToMany|Relation
     */
    protected function getRelation($relation)
    {
        return $this->modelInstance()->$relation();
    }

    public function tableName()
    {
        return $this->modelReflection()->tableName();
    }


}