<?php


namespace MezzoLabs\Mezzo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Permission\PermissionGuard;
use MezzoLabs\Mezzo\Exceptions\HttpException;
use MezzoLabs\Mezzo\Http\Controllers\ApiController;
use MezzoLabs\Mezzo\Http\Controllers\Controller;


class Request extends FormRequest
{

    /**
     * @var Request
     */
    protected static $current;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * Constructor.
     *
     * @param array $query The GET parameters
     * @param array $request The POST parameters
     * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array $cookies The COOKIE parameters
     * @param array $files The FILES parameters
     * @param array $server The SERVER parameters
     * @param string|resource $content The raw body data
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->boot();
    }

    /**
     * Called right after a request is constructed
     */
    protected function boot()
    {
        //Nothing to do here, but you can override this function in child classes.
    }


    /**
     * @return Request
     */
    public static function capture()
    {
        mezzo_dd('capture');
        if (!static::$current)
            static::$current = parent::capture();

        return static::$current;
    }

    /**
     * @return array
     */
    public static function allInput()
    {
        $request = app()->make(\Illuminate\Http\Request::class);
        return $request->all();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
     * Check if the current request comes via the API
     *
     * @return bool
     */
    public function isApi()
    {
        return $this->controller() instanceof ApiController;
    }

    /**
     * @return Controller
     * @throws HttpException
     */
    public function controller()
    {
        if (!$this->controller)
            $this->controller = $this->detectControllerFromRoute();

        return $this->controller;
    }

    protected function detectControllerFromRoute()
    {
        $actionData = (new Collection($this->route()->getAction()));
        $action = $actionData->get('controller');

        if (!$action)
            $action = $actionData->get('uses');

        if (!$action)
            throw new HttpException('No controller found for this request. ' .
                'We need a controller for cockpit requests because we want to validate them based on the class name.');

        $controller = explode('@', $action)[0];

        $controller = mezzo()->make($controller);

        if (!($controller instanceof Controller))
            throw new HttpException('The controller has to be ' . Controller::class);

        return $controller;
    }


    /**
     * @return PermissionGuard
     */
    protected function permissionGuard()
    {
        return app()->make(PermissionGuard::class);
    }


    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function all()
    {
        $array = array();
        foreach (parent::all() as $key => $value) {
            //replace names with dots with arrays.
            array_set($array, $key, $value);
        }
        return $array;
    }


}