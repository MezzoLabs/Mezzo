<?php


namespace MezzoLabs\Mezzo\Http\Controllers;


use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Modularisation\NamingConvention;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Core\Validation\ModelValidator;
use MezzoLabs\Mezzo\Exceptions\ModuleControllerException;
use MezzoLabs\Mezzo\Http\Exceptions\NoPermissionsException;
use MezzoLabs\Mezzo\Http\Requests\Request;

abstract class Controller extends IlluminateController
{

    use ValidatesRequests;

    /**
     * @var ModuleProvider
     */
    protected $module;
    /**
     * @var Collection
     */
    private $data;

    public function qualifiedActionName($method)
    {
        $this->hasActionOrFail($method);

        return '\\' . get_class($this) . '@' . $method;
    }

    /**
     * @param $method
     * @return bool
     * @throws ModuleControllerException
     */
    public function hasActionOrFail($method)
    {
        if (!$this->hasAction($method))
            throw new ModuleControllerException("The controller \"" . $this->qualifiedName() . "\"" .
                " doesn't support the action \"" . $method . "\".");

        return true;
    }

    /**
     * Check if a controller implements a certain action.
     *
     * @param $method
     * @return bool
     */
    public function hasAction($method)
    {
        if (!is_string($method))
            return false;

        return method_exists($this, $method);
    }

    /**
     * @return string
     */
    public function qualifiedName()
    {
        return get_class($this);
    }

    /**
     * @return string
     */
    public function slug()
    {
        $shortName = Singleton::reflection($this)->getShortName();

        $shortName = str_replace('Controller', '', $shortName);

        return camel_to_slug($shortName);
    }

    /**
     * @return bool
     */
    public function isResourceController()
    {
        if (!($this instanceof ResourceControllerContract))
            return false;

        if (!$this->isValid())
            return false;

        return true;
    }

    public function isValid()
    {
        if (!$this->module())
            throw new ModuleControllerException('A module controller has to be inside a module folder.');

        return true;
    }

    /**
     * @return ModuleProvider
     * @throws ModuleControllerException
     */
    public function module()
    {
        if (!$this->module)
            $this->module = NamingConvention::findModule($this);

        return $this->module;
    }

    /**
     * @param $class
     * @param $parameters
     * @return string
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     * @throws \MezzoLabs\Mezzo\Exceptions\ModulePageException
     */
    protected function page($class, $parameters = [])
    {
        $parameters = $this->data()->merge($parameters);

        $page = $this->module()->makePage($class);

        if (!$page->isAllowed()) {
            throw new NoPermissionsException('You are not allowed to view this page: ' . get_class($page));
        }

        return $page->template($parameters);
    }

    /**
     * @param null $key
     * @param null $value
     * @return Collection
     */
    public function data($key = null, $value = null)
    {

        if (!$this->data)
            $this->data = new Collection($this->defaultData());

        if ($key !== null && $value !== null) {
            $this->data->put($key, $value);
        }

        if (is_array($key))
            $this->addData($key);

        if ($key) {
            $this->data->get($key);
        }

        return $this->data;
    }

    protected function defaultData()
    {
        return [];
    }

    /**
     * Add data to the controller data, which will later be passed to the view.
     *
     * @param $toAdd
     * @return Collection|static
     */
    public function addData(array $toAdd)
    {
        $this->data = $this->data()->merge($toAdd);
        return $this->data;
    }

    /**
     * @param MezzoModelReflection $model
     * @param Request $request
     * @return ModelValidator
     */
    protected function modelValidator(MezzoModelReflection $model, Request $request)
    {
        return new ModelValidator($model, $request->all());
    }

    /**
     * @return \MezzoLabs\Mezzo\Http\Requests\Request
     */
    protected function request()
    {
        return mezzo()->makeRequest();
    }


}