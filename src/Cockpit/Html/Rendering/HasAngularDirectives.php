<?php

namespace MezzoLabs\Mezzo\Cockpit\Html\Rendering;


use Collective\Html\HtmlBuilder;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\ValidationRules\Rules;
use MezzoLabs\Mezzo\Modules\FileManager\Domain\TypedFiles\TypedFileAddon;

/**
 * Class HasAngularDirectives
 * @package MezzoLabs\Mezzo\Cockpit\Html\Rendering
 *
 * @property HtmlBuilder $html
 */
trait HasAngularDirectives
{
    /**
     * @param RelationAttribute $attribute
     * @param array $mergeHtmlAttributes
     * @return string
     */
    public function relationship(RelationAttribute $attribute, array $mergeHtmlAttributes = []) : string
    {
        $htmlAttributes = [
            'data-related' => $attribute->relationSide()->otherModelReflection()->name(),
            'data-scopes' => $attribute->relation()->getScopes()->toString(),
            'data-naming' => $attribute->relationSide()->naming(),
            'data-title' => $attribute->title(),
            'name' => $attribute->name()
        ];

        if ($attribute->hasMultipleChildren() && (!array_key_exists('multiple', $mergeHtmlAttributes) || $mergeHtmlAttributes['multiple'] !== null)
        ) $htmlAttributes[] = 'multiple';

        $validationRules = (new HtmlRules($attribute->rules()))->attributes()->toArray();

        $inputHtmlRules = $attribute->type()->htmlAttributes();

        $htmlAttributes = array_merge($htmlAttributes, $validationRules, $inputHtmlRules, $mergeHtmlAttributes);

        if ($htmlAttributes['readonly'] ?? false) {
            return $this->relationOutput($attribute, $htmlAttributes);
        }

        return '<mezzo-relation-input ' . $this->html->attributes($htmlAttributes) . '></mezzo-relation-input>';
    }

    /**
     * @param RelationAttribute $attribute
     * @param array $htmlAttributes
     * @return string
     */
    public function relationOutput(RelationAttribute $attribute, array $htmlAttributes = [])
    {
        return '<mezzo-relation-output ' . $this->html->attributes($htmlAttributes) . '></mezzo-relation-output>';
    }

    /**
     * @param string $name
     * @param TypedFileAddon $fileTypeModel
     * @param array $options
     * @return string
     */
    public function filePicker(string $name, TypedFileAddon $fileTypeModel, array $options = []) : string
    {
        $multiple = $options['multiple'] ?? false;
        $rules = $options['rules'] ?? new Rules();
        $mergeAttributes = $options['attributes'] ?? [];

        $htmlAttributes = [
            'data-file-type' => $fileTypeModel->fileType()->name(),
            'name' => $name
        ];

        if ($multiple) $htmlAttributes[] = 'multiple';


        $validationRules = (new HtmlRules($rules))->attributes()->toArray();
        $htmlAttributes = array_merge($htmlAttributes, $validationRules, $mergeAttributes);
        $htmlAttributesString = $this->html->attributes($htmlAttributes);

        return "<mezzo-file-picker {$htmlAttributesString}></mezzo-file-picker>";
    }
}