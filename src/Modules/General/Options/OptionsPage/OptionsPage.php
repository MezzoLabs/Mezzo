<?php


namespace MezzoLabs\Mezzo\Modules\General\Options\OptionsPage;

use MezzoLabs\Mezzo\Exceptions\ModulePageException;
use MezzoLabs\Mezzo\Http\Pages\ModulePage;
use MezzoLabs\Mezzo\Modules\General\Options\OptionFieldCollection;
use MezzoLabs\Mezzo\Modules\General\Options\OptionFieldRegistry;

abstract class OptionsPage extends ModulePage
{

    protected $optionFields = [

    ];


    protected function boot()
    {

    }

    /**
     * @return OptionFieldCollection
     * @throws ModulePageException
     */
    protected function optionFields()
    {
        $optionFields = new OptionFieldCollection();

        if(empty($this->optionFields))
            throw new ModulePageException('There are no options for this options page. Please set some fields in $optionFields.');

        foreach ($this->optionFields as $optionName) {
            $optionFields->add($this->fieldRegistry()->getOrDefault($optionName));
        }

        return $optionFields;
    }

    /**
     * @return OptionFieldRegistry
     */
    protected function fieldRegistry()
    {
        return $this->module()->optionRegistry();
    }


}