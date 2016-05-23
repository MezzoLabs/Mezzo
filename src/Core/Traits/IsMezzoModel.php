<?php
/**
 * Project: MezzoDemo | ModuleTrait.php
 * Author: Simon - www.triggerdesign.de
 * Date: 15.07.2015
 * Time: 20:21
 */

namespace MezzoLabs\Mezzo\Core\Traits;

/**
* @property string $mezzoTable
* @property string $mezzoRules
**/
trait IsMezzoModel
{

    /**
     * The associated model. Use the complete class name or "Default"
     *
     * @var string
     */
    protected $module = "Default";


    /**
     * Returns the class name of the module which contains this model.
     *
     * @return string
     */
    public function getModuleClass()
    {
        return $this->module;
    }



    
} 