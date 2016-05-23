<?php

namespace MezzoLabs\Mezzo\Http\Controllers;


use MezzoLabs\Mezzo\Core\Modularisation\Domain\Repositories\ModelRepository;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use MezzoLabs\Mezzo\Http\Requests\Resource\DestroyResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\IndexResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\InfoResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\ShowResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\StoreResourceRequest;
use MezzoLabs\Mezzo\Http\Requests\Resource\UpdateResourceRequest;
use Mezzolabs\Mezzo\Http\Responses\ApiResources\IndexResponse;
use Mezzolabs\Mezzo\Http\Responses\ApiResources\ResourceResponseFactory;
use MezzoLabs\Mezzo\Http\Responses\ApiResponseFactory;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Class HasApiResourceFunctions
 * @package MezzoLabs\Mezzo\Http\Controllers
 *
 * @method ApiResponseFactory response
 * @method boolean assertResourceExists($id)
 * @method ModelRepository repository()
 * @method MezzoModelReflection model()
 * @method ResourceResponseFactory resourceResponse()
 */
trait HasDefaultApiResourceFunctions
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexResourceRequest $request
     * @return ApiResponseFactory
     */
    public function index(IndexResourceRequest $request)
    {
        return $this->resourceResponse()->indexResource(QueryObject::makeFromResourceRequest($request), $this->repository());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreResourceRequest $request
     * @return ApiResponseFactory
     * @throws ModuleControllerException
     */
    public function store(StoreResourceRequest $request)
    {
        if (!$request->hasNestedRelations()) {
            $resource = $this->repository()->create($request->all());
        } else {
            $resource = $this->repository()->createWithNestedRelations($request->all(), $request->nestedRelations());
        }

        $response = $this->response()->item($resource, $this->bestModelTransformer());

        event('mezzo.api.store: ' . get_class($this), [$response]);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param ShowResourceRequest $request
     * @param int $id
     * @return ApiResponseFactory
     */
    public function show(ShowResourceRequest $request, $id)
    {
        $this->assertResourceExists($id);

        $response = $this->response()->item($this->repository()->findOrFail($id), $this->bestModelTransformer());

        event('mezzo.api.show: ' . get_class($this), [$response, $id]);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateResourceRequest $request
     * @param  int $id
     * @return ApiResponseFactory
     */
    public function update(UpdateResourceRequest $request, $id)
    {
        $this->assertResourceExists($id);

        if (!$request->hasNestedRelations()) {
            $resource = $this->repository()->update($request->all(), $id);
        } else {

            $resource = $this->repository()->updateWithNestedRelations($request->all(), $id, $request->nestedRelations());
        }

        $updated = $this->repository()->find($id);

        $response = $this->response()->item($updated, $this->bestModelTransformer());


        event('mezzo.api.update: ' . get_class($this), [$response, $id]);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyResourceRequest $request
     * @param  int $id
     * @return ApiResponseFactory
     */
    public function destroy(DestroyResourceRequest $request, $id)
    {
        $this->assertResourceExists($id);

        $delete = $this->repository()->delete($id);

        if($delete == 0){
            throw new ConflictHttpException('Delete failed.');
        }

        $response = $this->response()->result($delete);

        event('mezzo.api.destroy: ' . get_class($this), [$id]);

        return $response;
    }

    /**
     * @param InfoResourceRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function info(InfoResourceRequest $request)
    {

        return $this->response()->withArray($this->model()->schema()->toArray());
    }
}