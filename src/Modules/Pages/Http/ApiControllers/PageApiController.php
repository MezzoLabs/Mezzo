<?php

namespace MezzoLabs\Mezzo\Modules\Pages\Http\ApiControllers;


use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Controllers\ApiResourceController;
use MezzoLabs\Mezzo\Http\Controllers\HasDefaultApiResourceFunctions;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use StorePageRequest;
use UpdatePageRequest;

class PageApiController extends ApiResourceController
{
    use HasDefaultApiResourceFunctions {
        store as defaultStore;
        update as defaultUpdate;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResourceRequest $request
     * @return ApiResponseFactory
     * @throws ModuleControllerException
     */
    public function store(StorePageRequest $request)
    {
        $page = $this->repository()->createWithNestedRelations($request->all(), $request->formObject()->nestedRelations());

        $response = $this->response()->item($page, $this->bestModelTransformer());

        event('mezzo.api.store: ' . get_class($this), [$response]);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateResourceRequest $request
     * @param  int $id
     * @return ApiResponseFactory
     */
    public function update(UpdatePageRequest $request, $id)
    {

        return $this->defaultUpdate($request, $id);
    }
}