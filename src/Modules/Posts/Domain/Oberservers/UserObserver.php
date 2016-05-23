<?php

namespace MezzoLabs\Mezzo\Modules\Posts\Domain\Observers;


use Illuminate\Support\Facades\Auth;
use MezzoLabs\Mezzo\Modules\Posts\Domain\Repositories\PostRepository;

class UserObserver
{

    /**
     * @var PostRepository
     */
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }


    /**
     * Move the ownership of the created posts to the current user.
     *
     * @param \App\User $user
     */
    public function deleting(\App\User $user)
    {
        $this->posts->moveOwnership($user, Auth::user());

    }


}