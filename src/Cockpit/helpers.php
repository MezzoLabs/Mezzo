<?php

use MezzoLabs\Mezzo\Cockpit\Html\HtmlHelper;
use MezzoLabs\Mezzo\Cockpit\Html\Rendering\FormBuilder;

if (!function_exists('cockpit_asset')){
    /**
     * Get the URI to a specific asset (Css/Js/Image..).
     *
     * @param string $assetName
     * @return string
     */
    function cockpit_asset($assetName)
    {
        return '/mezzolabs/mezzo/cockpit' . $assetName;
    }
}

if (!function_exists('cockpit_stylesheet')) {
    /**
     * Create a link to a stylesheet for a specific asset.
     *
     * @param string $assetName
     * @return string
     */
    function cockpit_stylesheet($assetName)
    {
        return '<link rel="stylesheet" href="' . cockpit_asset($assetName) . '">';
    }
}

if (!function_exists('cockpit_script')) {
    /**
     * Create a script tag for a specific asset.
     *
     * @param string $assetName
     * @return string
     */
    function cockpit_script($assetName)
    {
        return '<script src="' . cockpit_asset($assetName) . '"></script>';
    }
}

if (!function_exists('cockpit_html')) {
    /**
     * Gives you a instance of the cockpit html helper class.
     *
     * @return HtmlHelper
     */
    function cockpit_html()
    {
        return new HtmlHelper();
    }
}

if (!function_exists('cockpit_view')) {
    /**
     * Gives you a instance of the cockpit html helper class.
     *
     * @return HtmlHelper
     */
    function cockpit_view(string $name)
    {
        return cockpit_html()->viewKey($name);
    }
}

if (!function_exists('angular_route')) {
    /**
     * Generate a URL to a named route.
     *
     * @param  string  $name
     * @param  array   $idParameter
     */
    function angular_route($name, $idParameter)
    {
        $uri = route($name, '||idParameter||');

        return str_replace('||idParameter||', '{{'. $idParameter .'}}', $uri);
    }
}

if (!function_exists('cockpit_form')) {
    /**
     * Retrieve the formbuilder instance.
     *
     * @return FormBuilder
     */
    function cockpit_form()
    {
        return app(FormBuilder::class);
    }
}
