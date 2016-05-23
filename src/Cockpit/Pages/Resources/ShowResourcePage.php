<?php


namespace MezzoLabs\Mezzo\Cockpit\Pages\Resources;


use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;

class ShowResourcePage extends ResourcePage
{
    protected $action = 'show';

    protected $view = 'cockpit::pages.resources.show';

    protected $options = [
        'visibleInNavigation' => false,
    ];

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

        return PermissionGuard::make()->allowsShow($this->model()->instance(true));
    }
}