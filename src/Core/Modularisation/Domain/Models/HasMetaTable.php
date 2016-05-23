<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as Collection;

/**
 * Class HasMetaTable
 * @package MezzoLabs\Mezzo\Core\Modularisation\Domain\Models
 *
 * @property integer $id
 * @property EloquentCollection $meta
 * @method HasMany meta()
 */
trait HasMetaTable
{
    /**
     * Created or updates in the model meta table.
     *
     * @param $key
     * @param $value
     * @param string $type
     * @return \App\UserMeta|\Illuminate\Database\Eloquent\Model
     */
    public function setMeta($key, $value, $type = null)
    {
        $key = snake_case($key);

        if ($value instanceof Collection) {
            $value = $value->toJson();
            $type = 'json';
        }

        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        if ($type == null) {
            $type = $this->determineMetaValueType($value);
        }

        return $this->meta()->updateOrCreate(['model_id' => $this->id, 'meta_key' => $key], [
            'model_id' => $this->id,
            'meta_key' => $key,
            'meta_type' => $type,
            'meta_value' => $value
        ]);
    }

    /**
     * @param $key
     * @return bool|null
     * @throws \Exception
     */
    public function unsetMeta($key)
    {
        if (!$this->hasMeta($key)) {
            return false;
        }

        return $this->getMetaObject($key)->delete();
    }


    public function metaValue($key, $default = null)
    {
        $found = $this->getMetaObject($key);

        if (!$found) {
            return $default;
        }


        return $this->castMetaValue($found);
    }

    private function determineMetaValueType($value)
    {
        if(is_integer($value)){
            return 'integer';
        }

        if(is_numeric($value)){
            return 'float';
        }

        return 'string';
    }

    /**
     * @param ModelMetaContract $meta
     * @return float|int|mixed|string|array
     */
    private function castMetaValue(ModelMetaContract $meta)
    {
        switch ($meta->meta_type) {
            case 'json':
                return json_decode($meta->meta_value, true);
            case 'integer':
                return intval($meta->meta_value);
            case 'float':
                return floatval($meta->meta_value);
            default:
                return $meta->meta_value;
        }
    }

    /**
     * @param $key
     * @return \Illuminate\Database\Eloquent\Model|\App\UserMeta
     */
    public function getMetaObject($key)
    {
        $key = snake_case($key);

        $index = $this->meta->search(function (ModelMetaContract $meta) use ($key) {
            return $meta->meta_key == $key;
        });


        if ($index === false) {
            return null;
        }

        return $this->meta->get($index);
    }

    public function hasMeta($key)
    {
        return !!$this->getMetaObject($key);
    }

}