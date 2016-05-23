<?php


namespace MezzoLabs\Mezzo\Cockpit\Pages\Resources;


use MezzoLabs\Mezzo\Cockpit\Pages\Forms\IndexTableColumn;
use MezzoLabs\Mezzo\Cockpit\Pages\Forms\IndexTableColumns;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;
use MezzoLabs\Mezzo\Core\Schema\Attributes\Attribute;

abstract class IndexResourcePage extends ResourcePage
{
    protected $action = 'index';

    protected $view = 'cockpit::pages.resources.index';

    protected $filtersView = false;

    protected $options = [
        'visibleInNavigation' => true,
        'appendToUri' => ''
    ];

    /**
     * Options that will be passed to the frontend controller in the vm.init function.
     *
     * @var array
     */
    protected $frontendOptions = [
        'backendPagination' => false
    ];

    /**
     * Returns the columns of the index table.
     *
     * @return array
     */
    public function columns() : IndexTableColumns
    {
        $attributes = $this->model()->attributes()->visibleInForm('index');

        $columns = new IndexTableColumns();
        $attributes->each(function (Attribute $attribute) use (&$columns) {
            $columns->put(
                $attribute->naming(),
                IndexTableColumn::makeFromAttribute($attribute)
            );
        });

        return $columns;
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

        return PermissionGuard::make()->allowsShow($this->model()->instance(true));
    }
}