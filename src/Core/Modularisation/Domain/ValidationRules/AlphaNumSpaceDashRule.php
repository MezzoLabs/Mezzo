<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\ValidationRules;


class AlphaNumSpaceDashRule extends AbstractValidationRule
{
    const KEY = "alpha_num_space_dash";

    /**
     * Execute this validation rule and check if the given attribute fits this rule.
     *
     * @return bool
     */
    public function execute() : bool
    {
        return preg_match('/^[\pL\s_\-0-9]+$/u', $this->value);
    }
}