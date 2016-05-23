<?php


namespace MezzoLabs\Mezzo\Modules\General\Options;


use Illuminate\Support\Collection;

class OptionFieldRegistry
{
    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function register($optionFields)
    {
        if (!is_array($optionFields))
            $optionFields = array($optionFields);

        foreach ($optionFields as $key => $optionField) {

            if(is_string($key) && is_array($optionField)){
                $optionField = OptionField::makeFromArray($key, $optionField);
            }

            $this->registerOptionField($optionField);
        }
    }

    protected function registerOptionField(OptionField $optionField)
    {
        $this->collection()->put($optionField->name(), $optionField);
    }

    /**
     * @param $name
     * @param $default
     * @return OptionField
     */
    public function get($name, $default = null)
    {
        return $this->collection()->get($name, $default);
    }

    /**
     * @param $name
     * @return OptionField
     */
    public function getOrDefault($name)
    {
        return $this->get($name, $this->defaultOptionField($name));
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @param $name
     * @return OptionField
     */
    public function defaultOptionField($name)
    {
        return new OptionField($name, []);
    }
}