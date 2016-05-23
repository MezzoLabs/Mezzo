<?php

namespace MezzoLabs\Mezzo\Modules\Categories\Http\Controllers;


use App\Tutorial;
use MezzoLabs\Mezzo\Http\Controllers\CockpitResourceController;
use MezzoLabs\Mezzo\Http\Requests\Resource\CreateResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\DestroyResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\EditResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Responses\ModuleResponse;
use MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories\CategoryGroupRepository;
use MezzoLabs\Mezzo\Modules\Categories\Domain\Repositories\CategoryRepository;
use MezzoLabs\Mezzo\Modules\Categories\Http\Pages\CategoryPage;
use packages\mezzolabs\mezzo\src\Modules\Posts\Http\Requests\StoreCategoryRequest;


class CategoryController extends CockpitResourceController
{
    public function defaultData()
    {
        return [
            'categories' => $this->repository()->all()->toTree(),
            'category_groups' => $this->groupRepository()->all()
        ];
    }

    /**
     * @return CategoryRepository
     */
    public function repository()
    {
        return parent::repository();
    }

    /**
     * @return CategoryGroupRepository
     */
    public function groupRepository()
    {
        return app(CategoryGroupRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ModuleResponse
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->page(CategoryPage::class);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResourceRequest $request
     * @return ModuleResponse
     */
    public function create(CreateResourceRequest $request)
    {
        return $this->page(CategoryPage::class);
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
        return $this->page(CategoryPage::class);
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
        return $this->page(CategoryPage::class);
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->repository()->create($request->all());

        return $this->redirectToPage(CategoryPage::class);
    }

    public function destroy(DestroyResourceRequest $request, $id)
    {
        $this->repository()->delete($id);

        return $this->redirectToPage(CategoryPage::class)->withMessage(trans('mezzo.modules.categories.messages.category_deleted'));

    }
}