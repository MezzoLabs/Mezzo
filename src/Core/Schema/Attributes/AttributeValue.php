<?php


namespace MezzoLabs\Mezzo\Core\Schema\Attributes;


use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\DateTimeInput;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\JsonInput;

class AttributeValue
{
    /**
     * @var Attribute|RelationAttribute
     */
    protected $attribute;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Creates a new value storage.
     *
     * @param $value
     * @param Attribute $attribute
     */
    public function __construct($value, Attribute $attribute)
    {
        $this->attribute = $attribute;

        $this->value = $value;


        $this->processValue();
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return Attribute|RelationAttribute
     */
    public function attribute()
    {
        return $this->attribute;
    }

    public function name()
    {
        return $this->attribute()->name();
    }

    public function isInteger()
    {
        return is_integer($this->value());
    }

    public function isEmptyRelation()
    {
        return $this->attribute->isRelationAttribute() && empty($this->value());
    }

    /**
     * Check if the join column is inside the main table.
     * This is true for 1:1 and 1:m relations where the join column is in the models table.
     *
     * @return bool
     */
    public function isOneChildRelationInMainTable()
    {
        if (!$this->isOneChildRelation())
            return false;

        return $this->attribute()->relationSide()->containsTheJoinColumn();
    }

    /**
     * Check if this attribute belongs to a 1:(1 or n) relation
     *
     * @return bool
     */
    public function isOneChildRelation()
    {
        if (!$this->attribute->isRelationAttribute())
            return false;

        return $this->attribute->hasOneChild();
    }

    /**
     * Check if this attribute is located in the main table.
     *
     * @return bool
     */
    public function isInMainTable()
    {
        return $this->attribute()->isAtomic() || $this->isOneChildRelationInMainTable();
    }

    /**
     * Process the given value from the form or the API to a eloquent friendly value.
     */
    protected function processValue()
    {
        // Change the value of unused select boxes (empty string "") to NULL
        if ($this->isOneChildRelationInMainTable() && empty($this->value))
            $this->value = NULL;

        // Cast the W3C format (YYYY-MM-DDTHH:MM) for datetime-local input to Laravels (YYYY-MM-DD HH:MM:SS)
        if ($this->attribute()->type() instanceof DateTimeInput) {
            $this->value = $this->processDateTimeValue($this->value);
        }

        if ($this->attribute()->type() instanceof JsonInput) {
            $this->value = $this->processJsonValue($this->value);
        }
    }

    /**
     * Process the value from the datetime-local form, so Carbon can handle it.
     *
     * @param $value
     * @return mixed|string
     */
    protected function processDateTimeValue($value)
    {
        return StringHelper::toDateTimeString($value);
    }


    protected function processJsonValue($value)
    {
        if (is_array($value)) {
            unset($value['_json']);

            foreach ($value as $key => $subValue) {
                $value[$key] = $this->processNumericValue($subValue);
            }

            return json_encode($value);
        }

        return $value;
    }

    protected function processNumericValue($value)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        $value = str_replace(',', '.', $value);

        if (str_contains($value, '.')) {
            return floatval($value);
        }

        return intval($value);
    }

    protected function isDateTimeString($value)
    {
        return StringHelper::toDateTimeString($value) != null;
    }
}