<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\ValidationRules;


class BooleanArrayRule extends AbstractValidationRule
{
    const KEY = "boolean_array";

    /**
     * Execute this validation rule and check if the given attribute fits this rule.
     *
     * @return bool
     */
    public function execute() : bool
    {
        if (!$this->value) {
            return true;
        }

        if (!is_array($this->value)) {
            return false;
        }

        foreach ($this->value as $key => $subValue) {
            $isBoolean = false;

            foreach ([1, 0, "1", "0", true, false, "true", "false", 'on', 'off'] as $booleanValue) {
                if ($subValue === $booleanValue) {
                    $isBoolean = true;
                    break;
                }
            }

            if (!$isBoolean) {
                return false;
            }

        }

        return true;
    }
}