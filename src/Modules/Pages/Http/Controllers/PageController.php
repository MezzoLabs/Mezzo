<?php

namespace MezzoLabs\Mezzo\Modules\Pages\Http\Controllers;

use App\User;
use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\Contents\Types\BlockTypes\ContentBlockTypeRegistrar;
use MezzoLabs\Mezzo\Modules\Pages\Http\Pages\CreatePagePage;
use MezzoLabs\Mezzo\Modules\Pages\Http\Pages\EditPagePage;
use MezzoLabs\Mezzo\Modules\Pages\Http\Pages\IndexPagePage;
use StorePageRequest;

class PageController extends CockpitResourceController
{
    protected function defaultData()
    {
        $blockRegistrar = ContentBlockTypeRegistrar::make();

        return [
            'blocks' => $blockRegistrar->all()
        ];
    }


    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->page(IndexPagePage::class);
    }


    /**
     * @param CreateResourceRequest $request
     * @return string
     */
    public function create(CreateResourceRequest $request)
    {
        return $this->page(CreatePagePage::class);
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
        //$model = $this->repository()->findOrFail($id, ['*'], ['content']);

        return $this->page(EditPagePage::class);
    }

    /**
     * @param StorePageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePageRequest $request)
    {
        $data = $request->all();
        $page = $this->repository()->createWithNestedRelations($data, $request->formObject()->nestedRelations());

        return $this->redirectToPage(EditPagePage::class, ['id' => $page->getAttribute('id')]);
    }


}