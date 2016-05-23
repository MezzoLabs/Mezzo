<?php

namespace MezzoLabs\Mezzo\Modules\Posts\Http\Controllers;

use App\Repositories\UserRepository;
use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\Posts\Http\Posts\CreatePostPage;
use MezzoLabs\Mezzo\Modules\Posts\Http\Posts\EditPostPage;
use MezzoLabs\Mezzo\Modules\Posts\Http\Posts\IndexPostPage;
use packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests\StorePostRequest;

class PostController extends CockpitResourceController
{
    /**
     * @var UserRepository
     */
    protected $users;

    public function __construct()
    {
        parent::__construct();

        $this->users = UserRepository::instance();

    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->page(IndexPostPage::class, [
            'backendUsers' => \App\User::backend()->get()
        ]);
    }


    /**
     * @param CreateResourceRequest $request
     * @return string
     */
    public function create(CreateResourceRequest $request)
    {
        return $this->page(CreatePostPage::class);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    public function show(ShowResourceRequest $request, $id)
    {
        // TODO: Implement show() method.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    public function edit(EditResourceRequest $request, $id = null)
    {
        return $this->page(EditPostPage::class);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->all();
        $post = $this->repository()->createWithNestedRelations($data, $request->formObject()->nestedRelations());

        return $this->redirectToPage(EditPostPage::class, ['id' => $post->getAttribute('id')]);
    }


}