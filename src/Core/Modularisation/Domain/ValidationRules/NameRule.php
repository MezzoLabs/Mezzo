<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\ValidationRules;


class NameRule extends AbstractValidationRule
{
    const KEY = "name";

    /**
     * Execute this validation rule and check if the given attribute fits this rule.
     *
     * @return bool
     */
    public function execute() : bool
    {
        return preg_match('/^([\pL]([\s-][\pL])?)+$/u', $this->value);
    }
}