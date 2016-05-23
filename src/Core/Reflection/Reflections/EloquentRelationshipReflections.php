<?php

namespace MezzoLabs\Mezzo\Core\Reflection\Reflections;

use Illuminate\Support\Collection;

class EloquentRelationshipReflections extends Collection
{

    /**
     * Filter the relations in this collection. No use for this function yet, but I think its nice to have.
     *
     * @param array $options
     * @return static
     */
    public function filterRelations($options = [])
    {
        return $this->filter(function (EloquentRelationshipReflection $reflection) use ($options) {
            $test = true;

            if (isset($options['type']) && !$reflection->is($options['type'])) {
                $test = false;
            }

            if (isset($options['localColumn']) && $options['localColumn'] != $reflection->localColumn()) {
                $test = false;
            }

            if (isset($options['relatedColumn']) && $options['relatedColumn'] != $reflection->localColumn()) {
                $test = false;
            }

            return $test;
        });

    }

    /**
     * Find the counterpart to a certain relation reflection inside this collection.
     *
     * @param EloquentRelationshipReflection $check
     * @return EloquentRelationshipReflection | null
     * @throws \ReflectionException
     */
    public function findCounterpartTo(EloquentRelationshipReflection $check)
    {
        $counterparts = $this->filter(function (EloquentRelationshipReflection $reflection) use ($check) {
            return $reflection->isCounterpart($check);
        });

        if ($counterparts->count() > 1) {
            throw new \ReflectionException('Found more than one counterpart for one relationship: ' .
                $check->qualifiedName());
        }

        if ($counterparts->count() == 0) {
            return null;
        }

        return $counterparts->first();
    }

} 