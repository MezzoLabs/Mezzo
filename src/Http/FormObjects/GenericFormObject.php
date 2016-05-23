<?php


namespace Mezzolabs\Mezzo\Cockpit\Http\FormObjects;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Helpers\ArrayAnalyser;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Validation\RulesTransformer;

class GenericFormObject implements FormObject
{
    const META_FIELDS = ['_token', '_method', '_json'];

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $data;

    /**
     * @var Collection
     */
    protected $metaInfo;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var ArrayAnalyser
     */
    protected $arrayAnalyser;

    /**
     * @param string $model
     * @param $data
     */
    public function __construct($model, $data)
    {
        $this->model = mezzo()->model($model);
        $this->data = new Collection($data);

        $this->arrayAnalyser = app(ArrayAnalyser::class);

        $this->processData();
    }

    protected function processData()
    {
        $this->convertJsonArrays();
        $this->removeMetaInfo();
        $this->convertCommaSeparatedIds();
        $this->convertCheckboxArrays();
    }


    /**
     * The reflection of the eloquent model.
     *
     * @return MezzoModelReflection
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Returns the data that was sent by the form request.
     *
     * @return Collection
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Returns a collection of nested relations data.
     *
     * @return NestedRelations
     */
    public function nestedRelations()
    {
        return $this->makeNestedRelations();
    }

    /**
     * Returns the table name of the models main table.
     *
     * @return string
     */
    public function table()
    {
        return $this->model()->tableName();
    }

    /**
     * Returns a collection with the data of the received attributes that are not inside a nested relation.
     *
     * @return Collection
     */
    public function atomicAttributesData()
    {
        return $this->data->filter(function ($value) {
            return !is_array($value);
        });
    }

    /**
     * @return NestedRelations
     */
    protected function makeNestedRelations()
    {
        $nested = new NestedRelations();

        $this->data->each(function ($value, $name) use ($nested) {
            if (!$this->isNestedRelation($value)) {
                return true;
            }

            $relationSide = $this->allRelationSides()->findOrFailByNaming($name);

            $nested->add(new NestedRelation($relationSide, $value));
        });

        return $nested;
    }

    public function isNestedRelation($data)
    {
        if (!is_array($data)) {
            return false;
        }

        if ($this->isIdsArray($data) || $this->isJsonArray($data) || $this->isPivotRowsArray($data)) {
            return false;
        }


        return true;
    }


    /**
     * @return \MezzoLabs\Mezzo\Core\Schema\Relations\RelationSides
     */
    public function allRelationSides()
    {
        return $this->model()->schema()->relationSides();
    }


    /**
     * Return all the rules of atomic attributes and nested relations in a dot notation.
     *
     * @return array
     */
    protected function rules()
    {
        $modelRules = $this->model()->rules();
        $relationRules = $this->nestedRelations()->rules();

        $rules = array_merge($modelRules, $relationRules);

        return $this->removeRedundantRequireRules($rules);
    }

    protected function removeRedundantRequireRules($rules)
    {
        $filteredRules = $rules;
        $this->nestedRelations()->each(function (NestedRelation $nestedRelation) use (&$filteredRules) {
            $parentRuleName = $nestedRelation->parentAttributeName();

            // Remove parent required rules if the nested relation is not empty e.g. remove address_id => required if
            // There is an address array in the request.
            if (!$nestedRelation->isEmpty() && isset($filteredRules[$parentRuleName])) {
                $filteredRules[$parentRuleName] = RulesTransformer::removeRequired($filteredRules[$parentRuleName]);
            }
        });


        return $filteredRules;
    }

    protected function convertCommaSeparatedIds()
    {
        foreach ($this->data as $key => $value) {
            if (!$this->model->schema()->hasAttribute($key))
                continue;

            if (!$this->model->schema()->attributes($key)->isRelationAttribute() || is_array($value))
                continue;

            if (str_contains($value, ','))
                $this->data[$key] = explode(',', $value);
        }
    }

    protected function convertCheckboxArrays()
    {
        foreach ($this->data as $key => $value) {
            if (!$this->isIdsArray($value)) continue;

            $isAssoc = array_values($value) === $value;
            if ($isAssoc) continue;

            $this->data->offsetSet($key, array_keys($value));

        }
    }

    private function convertJsonArrays()
    {
        foreach ($this->data as $key => $value) {
            if (!$this->isJsonArray($value)) continue;

            unset($value['_json']);


            $this->data->offsetSet($key, json_encode($value));

        }
    }

    /**
     * Check if an array is a list of ids
     *
     * @param $array
     * @return bool
     */
    protected function isIdsArray($array)
    {
        if (!is_array($array))
            return false;

        foreach ($array as $key => $value) {
            if (!is_numeric($key) || (!is_numeric($value) && $value != "on"))
                return false;
        }

        return true;
    }

    private function isJsonArray($value)
    {
        return is_array($value) && isset($value['_json']) && $value['_json'];
    }

    /**
     * Checks if an array is a pivot.
     *
     * E.g.: products[0] => id = 6, pivot_amount = 2
     *
     * @param array $array
     * @return bool
     */
    private function isPivotRowsArray(array $array)
    {
        return $this->arrayAnalyser->isPivotRowsArray($array);
    }

    protected function removeMetaInfo()
    {
        $this->metaInfo = new Collection();

        foreach (static::META_FIELDS as $metaKey) {
            $this->metaInfo->put($metaKey, $this->data->get($metaKey, ""));

            if ($this->data()->has($metaKey))
                $this->data()->offsetUnset($metaKey);
        }
    }


    /**
     * Return all the rules of atomic attributes and nested relations for a store request in a dot notation.
     *
     * @return array
     */
    public function rulesForStoring()
    {
        return Arr::dot($this->rules());
    }

    /**
     * Return all the rules of atomic attributes and nested relations for a update request in a dot notation.
     *
     * @param array $dirty
     * @return array
     */
    public function rulesForUpdating(array $dirty)
    {
        $rulesTransformer = new RulesTransformer($this->rules(), $this->getId());

        return $rulesTransformer->rulesForUpdating($dirty);
    }

    /**
     * Set the id of the resource that is changed by this form.
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }


}