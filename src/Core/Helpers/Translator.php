<?php


namespace MezzoLabs\Mezzo\Core\Helpers;


use Illuminate\Support\Facades\Lang;

class Translator
{
    /**
     * @param $keys
     * @return null|string
     */
    public static function find($keys)
    {
        if (!is_array($keys))
            $keys = [$keys];

        foreach ($keys as $key) {
            if (Lang::has(strtolower($key))) {
                return Lang::get(strtolower($key));
            }
        }

        return null;
    }

    public static function has($keys)
    {
        if (!is_array($keys))
            $keys = [$keys];

        foreach ($keys as $key) {
            if (Lang::has($key))
                return true;
        }
        return false;
    }
}