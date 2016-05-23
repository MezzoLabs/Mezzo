<?php


namespace MezzoLabs\Mezzo\Core\Schema\Rendering;


use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Collection\DecoratedCollection;

class AttributeRenderingOptions extends DecoratedCollection
{
    const NAME_MODE_DOTS = 'dots';
    const NAME_MODE_BRACKETS = 'brackets';

    /**
     * @return int|mixed
     */
    public function index()
    {
        if ($this->has('index'))
            return $this->get('index');

        if ($this->hasParent() && $this->parent()->getOptions()->has('index')) {
            return $this->parent()->getOptions()->get('index');
        }

        return 0;
    }

    /**
     *
     *
     * @return boolean | null
     */
    public function ngModel()
    {
        return $this->getFromThisOrParent('ngModel', null);
    }

    /**
     *
     */
    public function arraySeperators()
    {

    }

    /**
     * @return mixed
     */
    public function renderBefore()
    {
        return $this->get('wrap', true);
    }

    /**
     * @return mixed
     */
    public function renderAfter()
    {
        return $this->get('wrap', true);
    }

    /**
     * Check if this attribute renders inside the form of a relation.
     *
     * @return bool
     */
    public function isNested()
    {
        return $this->has('parent');
    }

    /**
     * @return AttributeRenderingHandler
     */
    public function parent()
    {
        return $this->get('parent', null);
    }

    /**
     * @return AttributeRenderingOptions|null
     */
    public function parentOptions()
    {
        if (!$this->hasParent())
            return null;

        return $this->parent()->getOptions();
    }

    public function nameMode()
    {
        return $this->get('nameMode', static::NAME_MODE_DOTS);
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return $this->has('parent');
    }

    /**
     * @return string
     * @throws AttributeRenderingException
     */
    public function parentName()
    {
        $parent = $this->parent();

        if (!$parent)
            throw new AttributeRenderingException('There is no parent attribute.');

        return $parent->relationSide()->naming();

    }

    /**
     * @return array
     */
    public function attributes() : array
    {
        $attributes = $this->get('attributes', []);


        return $attributes;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getAttribute($key, $default = null)
    {
        $attributes = new Collection($this->attributes());

        return $attributes->get($key, $default);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        $attributes = new Collection($this->attributes());

        return $attributes->has($key);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getFromThisOrParent($key, $default = null)
    {
        if ($this->has($key))
            return $this->get($key);


        if ($this->hasParent() && $this->parentOptions()->has($key))
            return $this->parent()->getOptions()->get($key);

        return $default;
    }

    public function required()
    {
        if ($this->hasParent() && $this->parent()->getOptions()->get('required', null) === false) {
            return false;
        }

        return $this->get('required', null);
    }
}