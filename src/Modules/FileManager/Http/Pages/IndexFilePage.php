<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Pages;

use MezzoLabs\Mezzo\Cockpit\Pages\Resources\IndexResourcePage;

class IndexFilePage extends IndexResourcePage
{
    protected $options = [
        'visibleInNavigation' => false,
        'appendToUri' => ''
    ];
}