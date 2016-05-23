<?php


namespace MezzoLabs\Mezzo\Cockpit\Html\Rendering;

use Illuminate\Support\Collection;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\CategoriesAttributeRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\CheckboxAttributeRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\CountryAttributeRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\PivotRowsRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\RelationAttributeMultipleRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\RelationAttributeSingleRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\RelationOutputRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\SelectableAttributeRenderer;
use Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers\SimpleAttributeRenderer;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;
use MezzoLabs\Mezzo\Core\Schema\Attributes\RelationAttribute;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderEngine as AbstractAttributeRenderEngine;
use MezzoLabs\Mezzo\Core\Schema\Rendering\AttributeRenderingHandler;

class AttributeRenderEngine extends AbstractAttributeRenderEngine
{
    public static $handlers = [
        CountryAttributeRenderer::class,
        CategoriesAttributeRenderer::class,
        PivotRowsRenderer::class,
        RelationOutputRenderer::class,
        RelationAttributeSingleRenderer::class,
        RelationAttributeMultipleRenderer::class,
        SelectableAttributeRenderer::class,
        CheckboxAttributeRenderer::class,
        SimpleAttributeRenderer::class
    ];

    protected $cssClass = 'form-control';

    /**
     * @return FormBuilder
     */
    public function formBuilder()
    {
        return app(FormBuilder::class);
    }

    /**
     * Generate the attributes that angular can use for validation.
     *
     * @param Attribute $attribute
     * @return Collection
     */
    public function validationAttributes(Attribute $attribute)
    {
        return (new HtmlRules($attribute->rules(), $attribute->type()))->attributes();
    }

    protected function relationAttributes(RelationAttribute $attribute)
    {
        $attributes = new Collection();

        $attributes->put('data-model', $attribute->otherRelationSide()->modelReflection()->name());
        $attributes->put('data-relation', $attribute->relation()->shortType());

        $attributes->put('data-multiple', ($attribute->hasMultipleChildren()) ? 1 : 0);

        if ($attribute->hasMultipleChildren())
            $attributes->put('multiple', 'multiple');

        return $attributes;
    }

    /**
     * Create an array of html attributes for this attribute schema.
     *
     * @param Attribute $attribute
     * @return array
     */
    public function htmlAttributes(Attribute $attribute)
    {
        $htmlAttributes = new Collection();

        // Add the default css class
        $htmlAttributes->put('class', $this->cssClass);
        // Add the HTML attributes that are given from the input type
        $htmlAttributes = $htmlAttributes->merge($attribute->type()->htmlAttributes());
        // Add the HTML attribute that are given from the validation rules
        $htmlAttributes = $htmlAttributes->merge($this->validationAttributes($attribute));

        // Add the HTML attributes that are given from the relation.
        if ($attribute->isRelationAttribute()) {
            $htmlAttributes = $htmlAttributes->merge($this->relationAttributes($attribute));
        }

        return $htmlAttributes->toArray();
    }

    public function defaultBefore(AttributeRenderingHandler $handler)
    {
        $groupClass = 'form-group';

        if ($handler->hasError())
            $groupClass .= ' has-error';

        $title = $handler->attribute()->title();

        if($handler->attribute()->rules()->has('required') || $handler->getOptions()->get('required') === true){
            $title .= ' *';
        }

        return '<div class="' . $groupClass . '"><label>' . $title . '</label>';
    }

    public function defaultAfter(AttributeRenderingHandler $handler)
    {
        return "</div>";
    }


}