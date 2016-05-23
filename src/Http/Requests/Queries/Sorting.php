<?php


namespace MezzoLabs\Mezzo\Http\Requests\Queries;


class Sorting
{
    const ASCENDING = "ASC";
    const DESCENDING = "DESC";

    /**
     * @var string
     */
    private $by;

    /**
     * @var string
     */
    private $direction;

    /**
     * Sorting constructor.
     * @param string $by
     * @param $direction
     */
    public function __construct(string $by, $direction)
    {
        $this->by = $by;
        $this->direction = $this->determineDirection($direction);
    }

    /**
     * The string for the sorting order that can be used in a mysql query.
     *
     * @return string
     */
    public function mysqlDirection() : string
    {
        return ($this->isAscending()) ? 'asc' : 'desc';
    }

    /**
     * @return bool
     */
    public function isAscending() : bool
    {
        return $this->direction == static::ASCENDING;
    }

    /**
     * @return bool
     */
    public function isDescending() : bool
    {
        return !$this->isAscending();
    }

    /**
     * @return string
     */
    public function by() : string
    {
        return $this->by;
    }

    /**
     * @param $direction
     * @return string
     */
    private function determineDirection($direction) : string
    {
        if (in_array(strtolower($direction), ["asc", "ascending", ""]))
            return static::ASCENDING;

        if (in_array(strtolower($direction), ["desc", "descending", "-"]))
            return static::DESCENDING;

        return static::ASCENDING;
    }


}