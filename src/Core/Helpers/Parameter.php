<?php


namespace MezzoLabs\Mezzo\Core\Helpers;


use MezzoLabs\Mezzo\Exceptions\ClassNotFoundException;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

class Parameter
{
    /**
     * Create a instance out of a variable using the Laravel IoC container.
     *
     * @param $variable
     * @param string $class
     * @return mixed
     * @throws ClassNotFoundException
     * @throws InvalidArgumentException
     * @throws MezzoException
     */
    public static function toInstance($variable, $class = "")
    {
        if (!empty($class) && $variable instanceof $class)
            return $variable;

        if (!is_string($variable))
            throw new InvalidArgumentException($variable);

        if (!class_exists($variable))
            throw new ClassNotFoundException($variable);

        $instance = app()->make($variable);

        if (!empty($class) && !$instance instanceof $class)
            throw new MezzoException('Instance of the class "' . get_class($instance) . '" was ' .
                'given but the function expected "' . $class . '".');

        return $instance;
    }

    public static function validateType($parameter, $allowedTypes)
    {
        if (!is_object($parameter))
            throw new InvalidArgumentException($parameter);

        if (!is_array($allowedTypes))
            $allowedTypes = [$allowedTypes];

        foreach ($allowedTypes as $allowedType) {
            if (get_class($parameter) === $allowedType) return true;
            if (get_parent_class($parameter) === $allowedType) return true;
        }

        throw new \InvalidArgumentException('Expected: ' . implode(' or ', $allowedTypes) . ' got ' . get_class($parameter));
    }
}