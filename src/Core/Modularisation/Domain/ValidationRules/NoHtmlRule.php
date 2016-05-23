<?php


namespace MezzoLabs\Mezzo\Core\Modularisation\Domain\ValidationRules;


class NoHtmlRule extends AbstractValidationRule
{
    const KEY = "no_html";

    /**
     * Execute this validation rule and check if the given attribute fits this rule.
     *
     * @return bool
     */
    public function execute() : bool
    {
        return preg_match('/^(?!.*<[^>]+>).*/', $this->value);
    }
}