<?php


namespace MezzoLabs\Mezzo\Core\Schema\ValidationRules;


use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Exceptions\InvalidArgumentException;
use MezzoLabs\Mezzo\Exceptions\MezzoException;

class Rules extends Collection
{
    /**
     * @param array|mixed $rulesToAdd
     * @return array|mixed|static
     * @throws InvalidArgumentException
     */
    public static function makeCollection($rulesToAdd)
    {
        if (is_array($rulesToAdd) || is_string($rulesToAdd)) {
            $rules = new static();
            $rules->addRulesFromString($rulesToAdd);
            return $rules;
        }

        if ($rulesToAdd instanceof Rules)
            return $rulesToAdd;

        if (!$rulesToAdd)
            return new static();

        throw new InvalidArgumentException($rulesToAdd);
    }

    /**
     * Add the rules from a Laravl validation rule string.
     *
     * @param $string |array
     * @return bool
     * @throws MezzoException
     */
    public function addRulesFromString($string)
    {
        if (empty($string))
            return false;

        if (!is_array($string))
            $string = explode('|', $string);

        foreach ($string as $ruleString) {
            $ruleArray = $this->parseRule($ruleString);

            $rule = Rule::makeFromRuleArray($ruleArray);
            $this->addRule($rule);
        }

        return true;
    }

    /**
     * Extract the rule name and parameters from a rule.
     *
     * @param  array|string $rules
     * @return array
     */
    protected function parseRule($rules)
    {
        if (is_array($rules)) {
            return $this->parseArrayRule($rules);
        }

        return $this->parseStringRule($rules);
    }

    /**
     * Parse an array based rule.
     *
     * @param  array $rules
     * @return array
     */
    protected function parseArrayRule(array $rules)
    {
        return [Str::studly(trim(Arr::get($rules, 0))), array_slice($rules, 1)];
    }

    /**
     * Parse a string based rule.
     *
     * @param  string $rules
     * @return array
     */
    protected function parseStringRule($rules)
    {
        $parameters = [];

        // The format for specifying validation rules and parameters follows an
        // easy {rule}:{parameters} formatting convention. For instance the
        // rule "Max:3" states that the value may only be three letters.
        if (strpos($rules, ':') !== false) {
            list($rules, $parameter) = explode(':', $rules, 2);

            $parameters = $this->parseParameters($rules, $parameter);
        }

        return [Str::studly(trim($rules)), $parameters];
    }

    /**
     * Parse a parameter list.
     *
     * @param  string $rule
     * @param  string $parameter
     * @return array
     */
    protected function parseParameters($rule, $parameter)
    {
        if (strtolower($rule) == 'regex') {
            return [$parameter];
        }

        return str_getcsv($parameter);
    }

    /**
     * Add a rule to the rule set for an attribute.
     *
     * @param Rule $rule
     * @throws MezzoException
     */
    public function addRule(Rule $rule)
    {
        $key = strtolower($rule->name());

        if ($this->has($key))
            throw new MezzoException('This rule already exists ' . $rule->name());

        $this->put($key, $rule);
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function has($key)
    {
        return parent::has(strtolower($key));
    }

    /**
     * @param mixed $key
     * @param null $default
     * @return Rule
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $ruleStrings = array();

        $this->each(function (Rule $rule) use (&$ruleStrings) {
            $ruleStrings[] = $rule->toString();
        });


        return implode('|', $ruleStrings);
    }

    public function isRequired()
    {
        return $this->has('required');
    }
}