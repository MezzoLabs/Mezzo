<?php


namespace MezzoLabs\Mezzo\Http\Controllers;

use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use Mezzolabs\Mezzo\Http\Responses\ApiResources\ResourceResponseFactory;
use MezzoLabs\Mezzo\Http\Transformers\EloquentModelTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ApiResourceController extends ApiController implements ResourceControllerContract
{
    use \MezzoLabs\Mezzo\Http\Controllers\HasModelResource;

    protected $allowStaticRepositories = false;

    public $model = "";

    /**
     * Find the best model transformer based on the class name and the registered transformers.
     * If there is no registration for the given model a new instance of "EloquentModelTransformer" will be returned.
     *
     * @param string $modelClass
     * @return EloquentModelTransformer
     */
    protected function bestModelTransformer($modelClass = "")
    {
        if (empty($modelClass))
            $modelClass = $this->model()->className();

        return EloquentModelTransformer::makeBest($modelClass);
    }

    /**
     * @param $id
     * @return NotFoundHttpException
     */
    public function assertResourceExists($id)
    {
        if (!$this->repository()->exists($id)) {
            throw new NotFoundHttpException();
        }

        return true;
    }


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

    /**
     * @return ResourceResponseFactory
     */
    public function resourceResponse()
    {
        return app()->make(ResourceResponseFactory::class);
    }
}