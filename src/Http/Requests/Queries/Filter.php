<?php


namespace MezzoLabs\Mezzo\Http\Requests\Queries;


class Filter
{
    /**
     * @var string
     */
    private $column;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(string $column, $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}