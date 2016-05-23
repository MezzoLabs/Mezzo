<?php

namespace MezzoLabs\Mezzo\Modules\Generator\Migration\Actions;


use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Modules\Generator\GeneratorException;

abstract class Action
{

    /**
     * The line that will be copied in the migration file inside the "up" function.
     *
     */
    abstract public function migrationUp();

    /**
     * The line that will be copied in the migration file inside the "down" function.
     *
     * @return string
     */
    abstract public function migrationDown();

    /**
     * Will return a qualified name that is different from all other possible actions.
     *
     * @return string
     */
    abstract public function qualifiedName();

    /**
     * Returns the name of the table which this action affects.
     *
     * @return string
     */
    abstract public function tableName();

    /**
     * Checks if the given action is a certain type (like "create", "update"...)
     *
     * @param $actionType string|Action
     * @throws GeneratorException
     * @throws InvalidArgumentException
     * @return bool
     */
    public function is($actionType)
    {
        if (is_string($actionType)) {
            $class = __NAMESPACE__ . '\\' . ucfirst($actionType) . 'Action';

            if (!class_exists($class))
                throw new GeneratorException('Generator Action ' . $actionType . ' is unknown.');
        } elseif (is_object($actionType)) {
            $class = get_class($actionType);
        } else {
            throw new InvalidArgumentException($actionType);
        }


        return get_class($this) === $class;
    }

} 