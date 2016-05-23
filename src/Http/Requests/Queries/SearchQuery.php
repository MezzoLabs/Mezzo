<?php


namespace MezzoLabs\Mezzo\Http\Requests\Queries;


class SearchQuery
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private $columns;

    /**
     * SearchQuery constructor.
     * @param string|string $value
     * @param array $columns
     */
    public function __construct(string $value, array $columns)
    {
        $this->value = $value;
        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    public function isEmpty()
    {
        return empty($this->value) || empty($this->columns);
    }

    /**
     * @return array
     */
    public function columns()
    {
        return $this->columns;
    }
}