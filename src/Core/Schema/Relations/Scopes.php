<?php


namespace MezzoLabs\Mezzo\Core\Schema\Relations;


use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use MezzoLabs\Mezzo\Core\Collection\StrictCollection;
use MezzoLabs\Mezzo\Core\Collection\StrictCollectionException;
use MezzoLabs\Mezzo\Core\Helpers\Parameter;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;

class Scopes extends StrictCollection
{
    public function addToQuery($query)
    {
        Parameter::validateType($query, [EloquentRelation::class, EloquentBuilder::class]);

        $this->each(function (Scope $scope) use ($query) {
            $scope->addToQuery($query);
        });

        return $query;
    }

    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof Scope;
    }

    /**
     * @param string $scopesString
     * @return Scopes
     * @throws InvalidArgumentException
     */
    public static function makeFromString(string $scopesString) : Scopes
    {
        $scopes = new static();

        $scopeStrings = explode('|', $scopesString);

        foreach ($scopeStrings as $scopeString) {
            if (empty($scopesString)) continue;

            $scope = Scope::buildFromString($scopeString);
            $scopes->add($scope);
        }

        return $scopes;
    }

    /**
     * @param array $scopesArray
     * @return Scopes
     * @throws InvalidArgumentException
     */
    public static function makeFromArray(array $scopesArray) : Scopes
    {

        $scopes = new static();

        foreach ($scopesArray as $name => $parameters) {
            if (empty($parameters))
                $parameters = [];

            $scopes->add(new Scope($name, $parameters));
        }

        return $scopes;
    }

    public static function make($scopes)
    {

        if (is_string($scopes))
            return static::makeFromString($scopes);

        if (is_array($scopes))
            return static::makeFromArray($scopes);

        throw new StrictCollectionException('Can only make scopes from strings or arrays.');
    }


    /**
     * Synonym for push.
     *
     * @param  mixed $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function add($value)
    {
        if (!$value instanceof Scope)
            throw new InvalidArgumentException($value);

        $this->put($value->name(), $value);
    }

    public function toString()
    {
        $scopes = [];
        $this->each(function (Scope $scope) use (&$scopes) {
            $scopes[] = $scope->toString();
        });

        return implode('|', $scopes);
    }
}