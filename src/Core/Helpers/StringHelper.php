<?php


namespace MezzoLabs\Mezzo\Core\Helpers;


use Carbon\Carbon;

class StringHelper
{
    const DATETIME_LOCAL = 'Y-m-d\TH:i:s';

    public static function fromArrayToDotNotation($arrayNotation)
    {
        $old = ['.', '[]', '[', ']'];
        $new = ['_', '', '.', ''];

        return str_replace($old, $new, $arrayNotation);
    }

    public static function datetimeLocal($date)
    {
        if (!$date instanceof \Carbon\Carbon)
            $date = new \Carbon\Carbon($date);

        return $date->format(static::DATETIME_LOCAL);
    }

    /**
     * Creates a path that will not have double or even missing slashes.
     *
     * @param string|array $directory
     * @param null $baseName
     * @return string
     */
    public static function path($directory, $baseName = null)
    {
        if (is_string($directory) && $baseName && is_string($baseName))
            return rtrim($directory, '/') . '/' . ltrim($baseName, '/');

        $path = "";
        if (is_array($directory)) {
            $i = 0;
            foreach ($directory as $d) {
                $i++;

                // Do not remove the first '/' if you have a collection of directory parts.
                if ($i == 1) {
                    $path .= rtrim($d, '/') . '/';
                    continue;
                }

                // Trim both sides of the directory part, but add a '/' on the right side.
                $path .= trim($d, '/') . '/';
            }

            // Trim the right side, so our complete path doesn't end with a '/' on the right side
            $path = rtrim($path, '/');
        }

        if (!$baseName)
            return $path;

        return $path . '/' . trim($baseName, '/');

    }

    public static function toDateTimeString($value)
    {
        if (!$value) {
            return null;
        }

        //DD.MM.YYY HH:mm:SS
        if (preg_match('/^\d{2}.\d{2}.\d{4} \d{2}:\d{2}$/', $value)) {
            return Carbon::createFromFormat('d.m.Y H:i', $value)->toDateTimeString();
        }

        //YYYY-MM-DDTHH:MM -> YYYY-MM-DD HH:MM:SS
        if (preg_match('/^\d{3,4}-\d{1,2}-\d{1,2}T\d{1,2}:\d{1,2}$/', $value))
            return str_replace('T', ' ', $value) . ':00';

        //YYYY-MM-DDTHH:MM:SS -> YYYY-MM-DD HH:MM:SS
        if (preg_match('/^\d{3,4}-\d{1,2}-\d{1,2}T\d{1,2}:\d{1,2}:\d{1,2}$/', $value))
            return str_replace('T', ' ', $value);

        //DD.MM.YYY HH:mm:SS
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        if ($value instanceof Carbon) {
            return $value->toDateTimeString();
        }


        return null;
    }

    public static function jsonDecode($string)
    {
        return json_decode($string);
    }

}