<?php


namespace MezzoLabs\Mezzo\Core\Helpers;


use Illuminate\Support\Str;

class ArrayAnalyser
{
    /**
     * Checks if an array is a pivot.
     *
     * E.g.: products[0] => id = 6, pivot_amount = 2
     *
     * @param array $array
     * @return bool
     */
    public function isPivotRowsArray(array $array)
    {
        foreach ($array as $key => $row) {

            if (!$this->isPivotRowArray($row)) {
                return false;
            }

        }


        return true;
    }

    public function isPivotRowArray($row)
    {
        if (!is_array($row)) {
            return false;
        }

        foreach ($row as $columnName => $columnValue) {
            if ($columnName != 'id' && !Str::startsWith($columnName, 'pivot_')) {
                return false;
            }
        }

        return true;
    }
}