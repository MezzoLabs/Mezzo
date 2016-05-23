<?php


namespace MezzoLabs\Mezzo\Http\Requests\Queries;

use MezzoLabs\Mezzo\Core\Collection\StrictCollection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;

class Filters extends StrictCollection
{


    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof Filter;
    }

    public static function makeByArray($parameters, MezzoModelReflection $reflection) : Filters
    {
        $filters = new static();

        foreach ($parameters as $key => $value) {

            if (!$reflection->attributes()->has($key))
                continue;

            $filters->add(new Filter($key, $value));
        }

        return $filters;
    }

    /**
     * Synonym for push.
     *
     * @param  mixed $value
     * @return $this
     */
    public function add($value)
    {
        if (!$value instanceof Filter)
            $this->fail($value);

        $this->put($value->column(), $value);
    }
}