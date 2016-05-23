<?php


namespace MezzoLabs\Mezzo\Http\Requests\Queries;


use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Collection\StrictCollection;

class Sortings extends StrictCollection
{
    /**
     * Check if a item can be part of this collection.
     *
     * @param $value
     * @return boolean
     */
    protected function checkItem($value)
    {
        return $value instanceof Sorting;
    }

    /**
     * Synonym for push.
     *
     * @param  mixed $value
     * @return $this
     */
    public function add($value)
    {
        if (!$value instanceof Sorting)
            $this->fail($value);

        $this->put($value->by(), $value);
    }

    /**
     * Generates a Sortings Collection out of the query parameter.
     * e.g: sort=-priority,created_at
     *
     * @param $sortingsString
     * @param array $searchableColumns
     * @return Sortings
     */
    public static function makeByString($sortingsString) : Sortings
    {
        $sortings = new static();

        foreach (explode(',', $sortingsString) as $sortingString) {
            if (empty($sortingString)) continue;

            $order = Sorting::ASCENDING;

            if (Str::startsWith($sortingString, '-')) {
                $order = Sorting::DESCENDING;
            }

            $by = trim($sortingString, '-');

            $sorting = new Sorting($by, $order);
            $sortings->add($sorting);
        }

        return $sortings;
    }
}