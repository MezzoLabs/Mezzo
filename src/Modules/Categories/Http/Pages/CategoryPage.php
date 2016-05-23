<?php


namespace MezzoLabs\Mezzo\Modules\Categories\Http\Pages;

use App\Category;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\ResourcePage;
use MezzoLabs\Mezzo\Modules\Categories\Http\Controllers\CategoryController;

class CategoryPage extends ResourcePage
{
    protected $action = "index";

    protected $controller = CategoryController::class;

    protected $model = Category::class;

    protected $view = 'modules.categories::pages.categories';

    protected $options = [
        'renderedByFrontend' => false,
        'visibleInNaviation' => true
    ];



}