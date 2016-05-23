<?php


namespace MezzoLabs\Mezzo\Cockpit\Pages\Resources;


use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;

class CreateResourcePage extends ResourcePage
{
    protected $action = 'create';

    protected $view = 'cockpit::pages.resources.create';

    protected $options = [
        'visibleInNavigation' => true,
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

        return PermissionGuard::make()->allowsCreate($this->model()->instance(true));
    }


}