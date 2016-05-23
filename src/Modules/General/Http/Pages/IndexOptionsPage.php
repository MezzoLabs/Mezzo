<?php


namespace MezzoLabs\Mezzo\Modules\General\Http\Pages;


use App\Option;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;

class IndexOptionsPage extends IndexResourcePage
{
    protected $model = Option::class;

    protected $action = "index";

    protected $view = "modules.general::pages.options";

    protected $options = [
        'renderedByFrontend' => false
    ];


}