<?php


namespace MezzoLabs\Mezzo\Core\Schema\Relations;

use MezzoLabs\Mezzo\Core\Collection\StrictCollection;
use MezzoLabs\Mezzo\Exceptions\ReflectionException;

class RelationSides extends StrictCollection
{

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof RelationSide;
    }

    /**
     * Find a relation side by its naming in the model class.
     *
     * @param $naming
     * @return null|RelationSide
     */
    public function findByNaming($naming)
    {
        $found = null;

        $this->each(function (RelationSide $relationSide) use ($naming, &$found) {
            if ($relationSide->naming() === $naming) {
                $found = $relationSide;
                return false;
            }
        });

        return $found;
    }

    /**
     * Find a relation by the naming or throw an exception.
     *
     * @param $naming
     * @return RelationSide|null
     * @throws ReflectionException
     */
    public function findOrFailByNaming($naming)
    {
        $found = $this->findByNaming($naming);

        if (!$found)
            throw new ReflectionException("There is no relation called \"{$naming}\"");

        return $found;
    }
}