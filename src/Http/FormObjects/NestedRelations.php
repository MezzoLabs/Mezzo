<?php


namespace Mezzolabs\Mezzo\Cockpit\Http\FormObjects;


use MezzoLabs\Mezzo\Core\Collection\StrictCollection;

class NestedRelations extends StrictCollection
{
    public function names()
    {
        $names = [];

        $this->each(function (NestedRelation $nestedRelation) use (&$names) {
            $names[$nestedRelation->name()] = $nestedRelation->name();
        });

        return $names;
    }

    public function savesBeforeParentIsCreated()
    {
        return $this->filter(function (NestedRelation $nestedRelation) {
            return $nestedRelation->savesBeforeParentIsCreated();
        });
    }

    public function savesAfterParentIsCreated()
    {
        return $this->filter(function (NestedRelation $nestedRelation) {
            return !$nestedRelation->savesBeforeParentIsCreated();
        });
    }

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof NestedRelation;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $this->each(function (NestedRelation $relation) use (&$rules) {
               $rules = array_merge($rules, $relation->rules());
        });

        return $rules;
    }
}