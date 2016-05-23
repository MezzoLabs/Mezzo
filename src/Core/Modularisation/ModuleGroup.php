<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 27.10.15
 * Time: 11:33
 */

namespace MezzoLabs\Mezzo\Core\Modularisation;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

class ModuleGroup
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var Collection
     */
    protected $modules;

    /**
     * Creates a group for modules.
     * This group will be represented in one section of the Cockpit sidebar.
     * A group has no influence on anything else than this styling.
     * Don't worry about the URI or stuff like that.
     *
     * @param $name
     * @param $label
     * @param null $modules
     */
    public function __construct($name, $label, $modules = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->modules = new Collection($modules);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * @return Collection
     */
    public function modules()
    {
        return $this->modules;
    }

    public function visibleModules()
    {
        return $this->modules->filter(function (ModuleProvider $module) {
            return $module->isVisible();
        });
    }

    public function isVisible()
    {
        return $this->visibleModules()->count() > 0;
    }

    /**
     * Add a module to this group.
     *
     * @param ModuleProvider $module
     */
    public function addModule(ModuleProvider $module)
    {
        $this->modules()->push($module);
    }

    /**
     * @return Collection
     */
    public static function groupsFromConfiguration()
    {
        $groupConfig = new Collection(mezzo()->config('moduleGroups'));

        /**
         * Make sure that there is a General group that will catch all the modules
         * that don't have a group set.
         */
        $general = $groupConfig->get('general', 'General');
        $groupConfig->put('general', $general);

        $groups = new Collection();

        foreach ($groupConfig as $name => $label) {
            $newGroup = new static($name, $label, []);

            $groups->put($name, $newGroup);
        }

        return $groups;
    }
}