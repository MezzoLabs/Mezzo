<?php


namespace MezzoLabs\Mezzo\Modules\Pages\Http\Pages;

use App\Page;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;

class IndexPagePage extends IndexResourcePage
{
    protected $model = Page::class;
}