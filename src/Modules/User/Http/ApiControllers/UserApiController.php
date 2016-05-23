<?php

namespace MezzoLabs\Mezzo\Modules\User\Http\ApiControllers;


use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Controllers\ApiResourceController;
use MezzoLabs\Mezzo\Http\Controllers\HasDefaultApiResourceFunctions;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use MezzoLabs\Mezzo\Modules\User\Http\Requests\StoreUserRequest;
use MezzoLabs\Mezzo\Modules\User\Http\Requests\UpdateUserRequest;

class UserApiController extends ApiResourceController
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
    public function store(StoreUserRequest $request)
    {
        if ($request->has('password'))
            $request->offsetSet('password', bcrypt($request->get('password')));

        return $this->defaultStore($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param  int $id
     * @return ApiResponseFactory
     */
    public function update(UpdateUserRequest $request, $id)
    {
        if ($request->has('password'))
            $request->offsetSet('password', bcrypt($request->get('password')));

        return $this->defaultUpdate($request, $id);
    }


}