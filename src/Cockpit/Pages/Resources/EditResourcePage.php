<?php


namespace MezzoLabs\Mezzo\Cockpit\Pages\Resources;


use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;
use MezzoLabs\Mezzo\Exceptions\ModulePageException;

class EditResourcePage extends ResourcePage
{
    protected $view = 'cockpit::pages.resources.edit';

    protected $action = 'edit';

    protected $options = [
        'visibleInNavigation' => false,
        'appendToUri' => '/{id}'
    ];

    public $frontendUrl = "";


    /**
     * @param ModuleProvider $module
     * @throws ModulePageException
     */
    public function __construct(ModuleProvider $module)
    {
        parent::__construct($module);

        //$this->frontendOption('canEdit', PermissionGuard::make()->allowsEdit($this->model()->instance()));
    }


    /**
     * Check if the current user is allowed to view this page.
     *
     * @return bool
     */
    public function isAllowed()
    {
        if (!parent::isAllowed()) {
            return false;
        }

        return PermissionGuard::make()->allowsEdit($this->model()->instance(true));
    }


}