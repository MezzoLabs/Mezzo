<?php

namespace MezzoLabs\Mezzo\Http\Controllers;

use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;

abstract class CockpitResourceController extends CockpitController implements ResourceControllerContract
{

    use HasModelResource;

    protected $allowStaticRepositories = false;

    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    abstract public function index(IndexResourceRequest $request);


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResourceRequest $request
     * @return ModuleResponse
     */
    abstract public function create(CreateResourceRequest $request);


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param ShowResourceRequest $request
     * @return ModuleResponse
     */
    abstract public function show(ShowResourceRequest $request, $id);

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    abstract public function edit(EditResourceRequest $request, $id);

    /**
     * Check if this resource controller is correctly named (<ModelName>Controller)
     *
     * @return bool
     * @throws ModuleControllerException
     */
    public function isValid()
    {
        parent::isValid();

        return $this->assertResourceIsReflectedModel();
    }

}