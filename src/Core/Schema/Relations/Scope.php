<?php


namespace MezzoLabs\Mezzo\Core\Schema\Relations;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use MezzoLabs\Mezzo\Core\Helpers\Parameter;
use MezzoLabs\Mezzo\Exceptions\EloquentScopeException;
use MezzoLabs\Mezzo\Exceptions\ReflectionException;

class Scope
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param $name
     * @param array $parameters
     * @throws ReflectionException
     */
    public function __construct($name, array $parameters = [])
    {
        if (empty($name))
            throw new ReflectionException('A scope needs to have a valid name.');

        $this->name = $name;


        $this->parameters = $this->fillParatemers($parameters);
        ksort($this->parameters);

    }

    private function fillParatemers($parameters)
    {
        if (empty($parameters))
            return $parameters;

        for ($i = 0; $i != 10; $i++) {
            if (isset($parameters[$i])) break;

            $parameters[$i] = "";
        }


        return $parameters;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    public function methodName()
    {
        return 'scope' . ucfirst($this->name());
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function hasParameters() : bool
    {
        return $this->parameterCount() > 0;
    }

    public static function buildFromString($scopeString)
    {
        $parts = explode(':', $scopeString);

        $name = $parts[0];

        $parameters = (count($parts) == 2) ? explode(',', $parts[1]) : [];

        return new static($name, $parameters);
    }

    public function parameterCount()
    {
        return count($this->parameters);
    }

    public function parameter($index = 0)
    {
        return $this->parameters[$index];
    }

    public function addToQuery($query)
    {
        Parameter::validateType($query, [EloquentRelation::class, EloquentBuilder::class]);

        $model = $query->getModel();
        if (!method_exists($model, $this->methodName())) {
            throw new EloquentScopeException("Unkown eloquent scope \"" . $this->name() . "\" in \"" . get_class($model) . "\"");
        }

        $scopeName = $this->name();

        switch ($this->parameterCount()) {
            case 0:
                return $query->$scopeName();
            case 1:
                return $query->$scopeName($this->parameter(0));
            case 2:
                return $query->$scopeName($this->parameter(0), $this->parameter(1));
            case 3:
                return $query->$scopeName($this->parameter(0), $this->parameter(1), $this->parameter(2));
            case 4:
                return $query->$scopeName($this->parameter(0), $this->parameter(1), $this->parameter(2), $this->parameter(3));
            default:
                throw new ReflectionException('Scopes with more than 4 parameters are not supported.');
        }

    }

    public function toString()
    {
        if (!$this->hasParameters())
            return $this->name();

        return $this->name() . ':' . implode(',', $this->parameters());
    }
}