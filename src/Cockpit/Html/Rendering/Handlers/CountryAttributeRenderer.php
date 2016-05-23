<?php


namespace Mezzolabs\Mezzo\Cockpit\Html\Rendering\Handlers;

use App\Country;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\CountryInput;
use MezzoLabs\Mezzo\Core\Schema\InputTypes\InputType;

class CountryAttributeRenderer extends SelectableAttributeRenderer
{
    public static $countries = ["Germany", "Austria", "France", "Switzerland", "Liechtenstein"];

    /**
     * Checks if this handler is responsible for rendering this kind of input.
     *
     * @param InputType $inputType
     * @return boolean
     */
    public function handles(InputType $inputType)
    {
        return $inputType instanceof CountryInput;
    }

    /**
     * Render the attribute to HTML.
     *
     * @param array $options
     * @return string
     */
    public function render(array $options = [])
    {
        $countries = \App\Country::all()->filter(function (Country $country) {
            return in_array($country->name, static::$countries);
        });

        $countries = $countries->sort(function ($country) {
            return array_search($country->name, static::$countries);
        });

        $countries->map(function (Country $country) {
            $country->name = $country->translatedName();
        });


        return $this->formBuilder()->select($this->name(), $countries->lists('name', 'iso_3166_2')->toArray(), $this->value('DE'), $this->htmlAttributes());
    }


}