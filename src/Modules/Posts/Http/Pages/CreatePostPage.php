<?php


namespace MezzoLabs\Mezzo\Modules\Posts\Http\Posts;


use MezzoLabs\Mezzo\Cockpit\Pages\Resources\CreateResourcePage;

class CreatePostPage extends CreateResourcePage
{
    protected $view = 'modules.posts::posts.create_or_edit';

}