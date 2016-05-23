<?php


namespace MezzoLabs\Mezzo\Modules\General\Http\Controllers;


use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\General\Http\Pages\IndexOptionsPage;
use MezzoLabs\Mezzo\Modules\General\Options\OptionsService;

class OptionController extends CockpitResourceController
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->page(IndexOptionsPage::class, ['options' => $this->repository()->all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResourceRequest $request
     * @return ModuleResponse
     */
    public function create(CreateResourceRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param ShowResourceRequest $request
     * @return ModuleResponse
     */
    public function show(ShowResourceRequest $request, $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditResourceRequest $request
     * @param  int $id
     * @return ModuleResponse
     */
    public function edit(EditResourceRequest $request, $id)
    {
    }

    public function store(StoreResourceRequest $request)
    {
        $this->options()->set($request->get('name'), $request->get('value'));
        return $this->redirectToPage(IndexOptionsPage::class)->with('message', 'Option created.');
    }

    public function options()
    {
        return app()->make(OptionsService::class);
    }
}