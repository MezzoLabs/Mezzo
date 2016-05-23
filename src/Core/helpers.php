<?php
use Illuminate\Support\Debug\Dumper;

if (!function_exists('mezzo')) {


    /**
     * Get the Mezzo core directly
     *
     * @return \MezzoLabs\Mezzo\Core\Mezzo
     */
    function mezzo()
    {
        return app()->make('mezzo');
    }
}

if (!function_exists('mezzo_path')) {

    /**
     * The path of the mezzo folder (...vendor/mezzolabs/mezzo)
     *
     * @return string
     */
    function mezzo_path()
    {
        return realpath(__DIR__ . "/../../");
    }
}

if (!function_exists('mezzo_source_path')) {

    /**
     * The path of the mezzo folder (...vendor/mezzolabs/mezzo/src)
     *
     * @return string
     */
    function mezzo_source_path()
    {
        return mezzo_path() . '/src/';
    }
}

if (!function_exists('mezzo_dump')) {
    /**
     * Dump the passed variables.
     *
     * @param $toDump
     * @param string $title
     * @return void
     */
    function mezzo_dump($toDump, $title = "", $stepsBack = 0)
    {
        if (!empty($title))
            $title = "<b>$title</b> ";

        $title .= '<small>(' . debug_backtrace()[$stepsBack]['file'] . ':' . debug_backtrace()[$stepsBack]['line'] . ')</small>';

        echo $title . ':<br/>';

        (new Dumper())->dump($toDump);
    }
}

if (!function_exists('mezzo_dd')) {
    /**
     * Dump the passed variables.
     *
     * @param $toDump
     * @param string $title
     * @return void
     */
    function mezzo_dd($toDump)
    {
        mezzo_dump($toDump, "", 1);
        die();
    }
}

if (!function_exists('mezzo_textdump')) {
    /**
     * Dump the passed variables.
     *
     * @param $toDump
     * @param string $title
     * @internal param $mixed
     * @return void
     */
    function mezzo_textdump($toDump)
    {
        echo '<pre>';
        echo str_replace('<?php', 'OPENING', $toDump);
        echo '<pre>';

    }
}

if (!function_exists('module_route')) {
    /**
     * @return \MezzoLabs\Mezzo\Core\Routing\Router
     */
    function module_route($module, $attributes, $callback)
    {
        mezzo()->makeRouter()->instance($module, $attributes, $callback);

    }
}

if (!function_exists('module_pages')) {
    /**
     * @return \MezzoLabs\Mezzo\Http\Pages\ModulePages|\MezzoLabs\Mezzo\Http\Pages\ModulePage
     */
    function module_pages($name = null)
    {
        return mezzo()->moduleCenter()->pages($name);

    }
}

if (!function_exists('space_case')) {
    /**
     * Transforms studly case to a readable text.
     *
     * @param $studlyCase
     * @return string
     */
    function space_case($studlyCase)
    {
        $space_case = strtolower(preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $studlyCase));

        return ucfirst($space_case);
    }
}

if (!function_exists('camel_to_slug')) {
    /**
     * Transforms camel case to a slug that separates big chars with a "-"
     *
     * @param $camelCase
     * @param string $separator
     * @return string
     */
    function camel_to_slug($camelCase, $separator = "-")
    {
        $space_case = space_case($camelCase);

        return str_slug($space_case, $separator);
    }
}


if (!function_exists('cockpit_content_container')) {
    /**
     * @return string
     */
    function cockpit_content_container()
    {
        return "cockpit::layouts.default.content.container";

    }
}

if (!function_exists('strip_umlaute')) {
    /**
     * @return string
     */
    function strip_umlaute($string)
    {
        $replacing = array(
            'ü' => 'ue', 'ä' => 'ae', 'ö' => 'oe', 'ß' => 'ss',
            'Ü' => 'Ue', 'Ä' => 'Ae', 'Ö' => 'Oe'
        );

        return str_replace(array_keys($replacing), array_values($replacing), $string);
    }
}

