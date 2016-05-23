<?php


namespace MezzoLabs\Mezzo\Core\Schema\ValidationRules;


use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

class Rule
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param string $name
     * @param array $parameters
     */
    private function __construct($name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    public static function makeFromRuleArray($ruleArray)
    {
        if (count($ruleArray) != 2)
            throw new MezzoException('Rule is not valid.');

        return new Rule($ruleArray[0], $ruleArray[1]);
    }

    /**
     * @return array
     */
    public function parameters($index = null)
    {
        if ($index === null)
            return $this->parameters;

        if (isset($this->parameters[$index]))
            return $this->parameters[$index];

        throw new MezzoException('The index ' . $index . ' does not exist for this rule.');
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (!$this->hasParameters()) return $this->snakeName();

        return $this->snakeName() . ':' . implode(',', $this->parameters);

    }

    public function hasParameters()
    {
        return count($this->parameters) > 0;
    }

    /**
     * Get the name of this rule in snake case.
     *
     * @return string
     */
    protected function snakeName()
    {
        return Str::snake($this->name());
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }


}