<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Http\Pages;

use App\CategoryGroup;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;
use MezzoLabs\Mezzo\Modules\Categories\Http\Controllers\CategoryGroupController;

class CategoryGroupPage extends IndexResourcePage
{
    protected $action = "index";

    protected $controller = CategoryGroupController::class;

    protected $model = CategoryGroup::class;



}