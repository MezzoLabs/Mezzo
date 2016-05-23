<?php
/**
 * Created by: simon.schneider
 * Date: 16.09.2015 - 11:11
 * Project: MezzoDemo
 */


namespace MezzoLabs\Mezzo\Core\Helpers;


use MezzoLabs\Mezzo\Core\Mezzo;

class Path
{

    /**
     * @var Mezzo
     */
    private $mezzo;

    public function __construct(Mezzo $mezzo)
    {

        $this->mezzo = $mezzo;
    }

    /**
     * The path of the mezzo folder (...vendor/mezzolabs/mezzo)
     *
     * @return string
     */
    public function toMezzo()
    {
        return mezzo_path();
    }

    /**
     * The path of the mezzo folder (...vendor/mezzolabs/mezzo/src)
     *
     * @return string
     */
    public function toSource()
    {
        return mezzo_source_path();
    }

    /**
     * The path to the app directory
     *
     * @return string
     */
    public function toApp()
    {
        return app_path();
    }

    /**
     * The path to the app directory
     *
     * @return string
     */
    public function toMezzoGenerated()
    {
        return app_path() . '/Mezzo/Generated';
    }

    /**
     * The path to the root folder
     *
     * @return string
     */
    public function toBaseDirectory()
    {
        return base_path();
    }

    /**
     * The path the the 'database' directory.
     *
     * @return string
     */
    public function toDatabaseDirectory()
    {
        return database_path();
    }


    public function toModule($moduleName)
    {
        $moduleKey = $this->mezzo->moduleCenter()->moduleKey($moduleName);

        $moduleName = studly_case($moduleName);

        return $this->toSource() . 'Modules/' . $moduleName;

    }
} 