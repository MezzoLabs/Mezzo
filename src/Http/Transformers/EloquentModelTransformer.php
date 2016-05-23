<?php


namespace MezzoLabs\Mezzo\Http\Transformers;


use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Modularisation\NamingConvention;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Schema\Attributes\AttributeValue;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\JsonInput;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\TransformerException;


abstract class EloquentModelTransformer extends ModelTransformer
{
    /**
     * The class name of the model which is handled by this transformer.
     *
     * @var string
     */
    protected $modelName;

    /**
     * @var MezzoModelReflection
     */
    protected $model;

    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = array();

    /**
     * Include resources without needing it to be requested.
     *
     * @var array
     */
    protected $defaultIncludes = array();

    /**
     * @param null $modelName
     * @throws \MezzoLabs\Mezzo\Exceptions\NamingConventionException
     */
    public function __construct($modelName = null)
    {
        if (!$modelName)
            $modelName = NamingConvention::modelName($this);

        $this->modelName = $modelName;

        $this->addRelationsAsIncludes();
    }

    /**
     * Adds the relations of the associated model to the available includes.
     * You can then use them in the ?include parameter.
     * Fractal will call include<RelationName> - this will be catched by the magic function.
     */
    protected function addRelationsAsIncludes()
    {
        $relationAttributes = $this->model()->attributes()->relationAttributes();

        $relationAttributes->each(function (RelationAttribute $attribute) {
            $this->availableIncludes[] = $attribute->relationSide()->naming();
        });

        $this->availableIncludes = array_unique($this->availableIncludes);
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection
     */
    protected function model()
    {
        return mezzo()->model($this->getModelName());
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param string $modelName
     */
    public function setModelName($modelName)
    {

        $this->modelName = $modelName;
    }

    /**
     * @param MezzoModel $model
     * @return array
     * @throws InvalidArgumentException
     */
    public function transform($model)
    {
        if (!$model instanceof MezzoModel)
            throw new InvalidArgumentException($model);

        $returnCollection = new Collection();

        $attributeValues = $model->attributeValues()->inMainTableOnly()->visibleOnly();

        $attributeValues->each(function (AttributeValue $attributeValue) use ($returnCollection) {
            $value = $this->transformValue($attributeValue);
            $returnCollection->put($attributeValue->name(), $value);
        });


        $returnCollection = $returnCollection->merge($model->getPivotValues());

        $returnCollection->put('id', $model->id);

        $returnCollection = $returnCollection->merge($this->pluginsData($model));

        return $returnCollection->toArray();
    }


    /**
     * @param AttributeValue $attributeValue
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function transformValue(AttributeValue $attributeValue)
    {
        $value = $attributeValue->value();

        if ($attributeValue->attribute()->type() instanceof JsonInput) {
            return StringHelper::jsonDecode($value);
        }

        if (!is_object($value))
            return $value;

        $transformer = $this->registrar()->findTransformerClass($value);

        if (!$transformer)
            return $value;

        return app($transformer)->transform($value);
    }

    /**
     * @return TransformerRegistrar
     */
    protected static function registrar()
    {
        return TransformerRegistrar::make();
    }

    /**
     * @param MezzoModel $model
     * @return Collection
     */
    protected function pluginsData(MezzoModel $model)
    {
        return $this->registrar()->callPlugins($model);
    }

    /**
     * Call magic methods beginning with "with".
     *
     * @param string $method
     * @param array $parameters
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'include'))
            return $this->callMagicInclude(Str::camel(substr($method, 7)), $parameters[0]);
    }

    /**
     * @param $relationName
     * @param MezzoModel $model
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\Item
     * @throws TransformerException
     */
    protected function callMagicInclude($relationName, MezzoModel $model)
    {
        if (!in_array($relationName, $this->availableIncludes))
            throw new TransformerException('Cannot call magic include ' . $relationName . ' ' .
                'because this relation is not in the available includes.');

        $relationElements = $model->$relationName;

        if ($relationElements instanceof EloquentCollection)
            return $this->automaticCollection($relationElements);

        if ($relationElements instanceof MezzoModel)
            return $this->automaticItem($relationElements);
    }

    /**
     * @param EloquentCollection|EloquentRelation $collection
     * @return \League\Fractal\Resource\Collection
     * @throws InvalidArgumentException
     */
    protected function automaticCollection($collection)
    {
        $modelClass = "";
        if ($collection instanceof EloquentRelation)
            $modelClass = get_class($collection->getRelated());

        if ($collection instanceof EloquentCollection && !$collection->isEmpty()) {
            $modelClass = get_class($collection->first());
        }

        $transformer = $this->makeBest($modelClass);

        return $this->collection($collection, $transformer);
    }

    /**
     * @param $modelClass
     * @return EloquentModelTransformer
     */
    public static function makeBest($modelClass)
    {
        if ($modelClass == "") {
            return new Transformer();
        }

        $registrar = TransformerRegistrar::make();

        $transformerClass = $registrar->findTransformerClass($modelClass);

        if (!$transformerClass)
            return new GenericEloquentModelTransformer($modelClass);

        $transformer = app()->make($transformerClass, [$modelClass]);
        return $transformer;
    }

    protected function automaticItem(MezzoModel $model)
    {
        $transformer = $this->makeBest(get_class($model));

        return $this->item($model, $transformer);
    }

    /**
     * @return mixed|null|string
     * @throws \MezzoLabs\Mezzo\Exceptions\NamingConventionException
     */
    protected function defaultModelName()
    {
        if ($this->modelName)
            return $this->modelName;

        return NamingConvention::modelName($this);
    }
}