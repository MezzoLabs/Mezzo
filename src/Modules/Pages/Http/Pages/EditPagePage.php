<?php


namespace MezzoLabs\Mezzo\Modules\Pages\Http\Pages;

use App\Page;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\EditResourcePage;

class EditPagePage extends EditResourcePage
{
    protected $model = Page::class;

    protected $options = [
        'visibleInNavigation' => false,
        'renderedByFrontend' => true,
        'appendToUri' => '/{id}'
    ];

}