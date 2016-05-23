<?php


namespace MezzoLabs\Mezzo\Http\Requests\Resource;

use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Controllers\Controller;
use MezzoLabs\Mezzo\Http\Controllers\ResourceControllerContract;
use MezzoLabs\Mezzo\Http\Requests\Queries\QueryObject;
use MezzoLabs\Mezzo\Http\Requests\Request;
use MezzoLabs\Mezzo\Http\Requests\ValidatesApiRequests;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ResourceRequest extends Request
{
    use ValidatesApiRequests;

    public $model = "";

    /**
     * @var MezzoModelReflection
     */
    protected $modelReflection = null;

    private $currentModelInstance = null;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection
     * @throws ModuleControllerException
     */
    public function modelReflection() : MezzoModelReflection
    {
        if (!$this->modelReflection) {
            $this->modelReflection = $this->findModelReflection();
        }

        return $this->modelReflection;
    }

    public function newModelInstance()
    {
        return $this->modelReflection()->instance(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function currentModelInstance()
    {
        if (!$this->currentModelInstance) {
            $id = $this->getId();

            if (!$this->hasValidID()) {
                throw new BadRequestHttpException('This request needs an id.');
            }

            $this->currentModelInstance = $this->modelReflection()->instance()->query()->findOrFail(intval($id));
        }

        return $this->currentModelInstance;
    }

    public function hasValidModelInstance()
    {
        if (!$this->hasValidID()) {
            return false;
        }


        if (!$this->modelReflection()->repository()->exists($this->getId())) {
            return false;
        }

        return true;
    }

    public function hasValidID()
    {
        $id = $this->getId();

        return $id && is_numeric($id);
    }

    public function getId()
    {
        $id = $this->route('id');
        if (!$id) $id = $this->get('id');

        return $id;
    }

    /**
     * @return \MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection
     * @throws ModuleControllerException
     */
    protected function findModelReflection()
    {
        if (!empty($this->model)) {
            return mezzo()->model($this->model, 'mezzo');
        }

        return $this->controller()->model();
    }

    /**
     * @return Controller|ResourceControllerContract
     * @throws ModuleControllerException
     */
    public function controller()
    {
        $controller = parent::controller();

        if (empty($this->model) && !($controller instanceof ResourceControllerContract))
            throw new ModuleControllerException('The controller ' . $controller->qualifiedName() . ' uses a ' .
                'Resource Request. For this we need to detect the resource that the controller manages. ' .
                'Please use a correctly named ResourceController.');

        return $controller;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator|\Illuminate\Validation\Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        if (!$this->isApi()) {
            throw new HttpResponseException($this->response(
                $this->formatErrors($validator)
            ));
        }


        return $this->failedApiValidation($validator);
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return mixed
     */
    protected function failedAuthorization()
    {
        if (!$this->isApi())
            throw new UnauthorizedException;

        return $this->failedApiAuthorization();
    }

    protected function isTemplateRequest()
    {
        $action = $this->route()->getAction();

        if (!isset($action['as']))
            return false;

        return Str::endsWith($action['as'], '_html');
    }

    public function queryObject()
    {
        return QueryObject::makeFromResourceRequest($this);
    }


}