<?php


namespace MezzoLabs\Mezzo\Core\Schema\Attributes;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Collection\StrictCollection;
use MezzoLabs\Mezzo\Core\Modularisation\Domain\Models\MezzoModel;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;
use MezzoLabs\Mezzo\Exceptions\HttpException;

class AttributeValues extends StrictCollection
{
    /**
     * @param MezzoModel $model
     * @return AttributeValues
     * @throws HttpException
     */
    public static function fromModel(MezzoModel $model)
    {
        $rawValues = $model->getAttributes();

        $values = [];
        foreach ($rawValues as $valueName => $value) {
            $values[$valueName] = $model->$valueName;
        }

        return static::fromArray($model->schema(), $values);
    }

    /**
     * @param ModelSchema $model
     * @param array $array
     * @return AttributeValues
     * @throws HttpException
     */
    public static function fromArray(ModelSchema $model, $array, bool $strict = false)
    {
        $values = new AttributeValues();

        foreach ($array as $key => $value) {
            $attribute = $model->attributes($key);

            if (in_array($key, ["_token", "_method", 'include']) || static::isConfirmation($key, $array))
                continue;

            if (!$attribute && !$strict) {
                continue;
            }

            if (!$attribute && $strict) {
                throw new HttpException("\"" . $key . "\" is not a valid attribute in " . $model->className());
            }

            $value = new AttributeValue($value, $attribute);
            $values->add($value);
        }

        return $values;
    }

    private static function isConfirmation($key, $array)
    {
        if (!Str::endsWith($key, '_confirmation'))
            return false;

        $without_confirmation = str_replace('_confirmation', '', $key);

        return in_array($without_confirmation, array_keys($array));
    }


    /**
     * @param ModelSchema $model
     * @param array $data
     * @return AttributeValues
     * @throws HttpException
     */
    public static function fromInput(ModelSchema $model, $data = [])
    {
        if (empty($data))
            $data = Input::all();

        return static::fromArray($model, $data);
    }

    /**
     * Only return scalar attributes that represent a column in the main table of the model.
     *
     * @return AttributeValues
     */
    public function inMainTableOnly()
    {
        return $this->filter(function (AttributeValue $value) {
            return $value->isInMainTable();
        });
    }

    /**
     * Only return attributes that have to be updated on foreign tables.
     *
     * @return AttributeValues
     */
    public function inForeignTablesOnly()
    {
        return $this->filter(function (AttributeValue $value) {
            return !$value->isInMainTable();
        });
    }

    /**
     * Returns a filtered Value collection that only consists out of atomic attributes.
     *
     * @return AttributeValues
     */
    public function atomicOnly()
    {
        return $this->filter(function (AttributeValue $value) {
            return $value->attribute()->isAtomic();
        });
    }

    /**
     * Returns a filtered Value collection that only consists out of relation attributes.
     *
     * @return AttributeValues
     */
    public function relationsOnly()
    {
        return $this->filter(function (AttributeValue $value) {
            return $value->attribute()->isRelationAttribute();
        });
    }

    /**
     * Returns a filtered Value collection that only consists out of visible attributes.
     *
     * @return AttributeValues
     */
    public function visibleOnly()
    {
        return $this->filter(function (AttributeValue $value) {
            return $value->attribute()->isVisible();
        });
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];

        $this->each(function (AttributeValue $value) use (&$array) {
            $array[$value->name()] = $value->value();
        });

        return $array;
    }

    protected function checkItem($value)
    {
        return $value instanceof AttributeValue;
    }
}