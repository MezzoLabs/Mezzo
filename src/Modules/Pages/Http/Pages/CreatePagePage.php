<?php


namespace MezzoLabs\Mezzo\Modules\Pages\Http\Pages;

use App\Page;
use MezzoLabs\Mezzo\Cockpit\Pages\Resources\CreateResourcePage;

class CreatePagePage extends CreateResourcePage
{
    protected $model = Page::class;


}