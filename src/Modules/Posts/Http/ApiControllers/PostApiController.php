<?php

namespace MezzoLabs\Mezzo\Modules\Posts\Http\ApiControllers;


use App\LockableResources\HandlesLockableApiResources;
use MezzoLabs\Mezzo\Http\Controllers\ApiResourceController;
use MezzoLabs\Mezzo\Http\Controllers\HasDefaultApiResourceFunctions;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests\StorePostRequest;
use packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests\UpdatePostRequest;

class PostApiController extends ApiResourceController
{
    use HasDefaultApiResourceFunctions {
        store as defaultStore;
        update as defaultUpdate;
    }

    use HandlesLockableApiResources;


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResourceRequest|StorePostRequest $request
     * @return ApiResponseFactory
     */
    public function store(StorePostRequest $request)
    {
        $page = $this->repository()->createWithNestedRelations($request->all(), $request->formObject()->nestedRelations());

        $response = $this->response()->item($page, $this->bestModelTransformer());

        event('mezzo.api.store: ' . get_class($this), [$response]);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateResourceRequest|UpdatePostRequest $request
     * @param  int $id
     * @return ApiResponseFactory
     */
    public function update(UpdatePostRequest $request, $id)
    {
        return $this->defaultUpdate($request, $id);
    }


}