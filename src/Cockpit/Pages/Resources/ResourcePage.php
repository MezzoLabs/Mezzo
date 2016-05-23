<?php


namespace MezzoLabs\Mezzo\Cockpit\Pages\Resources;


use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Modularisation\ModuleProvider;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\MezzoModelReflection;
use MezzoLabs\Mezzo\Exceptions\CannotGuessModelException;
use MezzoLabs\Mezzo\Exceptions\ModulePageException;
use MezzoLabs\Mezzo\Http\Pages\ModulePage;

abstract class ResourcePage extends ModulePage
{
    protected static $types = ['create', 'edit', 'index', 'show'];

    /**
     * @var string
     */
    protected $model = "";

    protected $options = [
        'renderedByFrontend' => true,
        'visibleInNaviation' => true
    ];
    /**
     * @var MezzoModelReflection
     */
    private $modelReflection;

    /**
     * Options that will be passed to the frontend controller in the vm.init function.
     *
     * @var array
     */
    protected $frontendOptions = [];

    /**
     * @param ModuleProvider $module
     * @throws ModulePageException
     */
    public function __construct(ModuleProvider $module)
    {
        $this->module = $module;

        if (empty($this->controller))
            $this->controller = $this->guessController();

        parent::__construct($module);


        $this->assertThatPageHasModel();

    }

    /**
     * @return \MezzoLabs\Mezzo\Http\Controllers\ResourceControllerContract
     * @throws \MezzoLabs\Mezzo\Exceptions\ModuleControllerException
     */
    protected function guessController()
    {
        return $this->module()->resourceController($this->model()->name() . 'Controller');
    }

    /**
     * @return MezzoModelReflection
     */
    public function model()
    {
        if (!$this->modelReflection) {
            $this->modelReflection = $this->makeModelReflection();
        }

        return $this->modelReflection;
    }

    /**
     *
     */
    protected function makeModelReflection()
    {
        if (!empty($this->model)) {
            return mezzo()->model($this->model);
        }

        return mezzo()->model($this->guessModel());
    }

    /**
     * If there is no model set as a property for this page, we will try to guess it from the
     * class name of this page.
     *
     * E.g:
     * List<ModelName>Page, Edit<ModelName>Page, List<ModelName>Page.php
     *
     * @return mixed
     * @throws CannotGuessModelException
     */
    protected function guessModel()
    {
        $pageName = strtolower(Singleton::reflection($this)->getShortName());

        $possibleModel = str_replace(static::$types, '', $pageName);

        if (Str::endsWith($possibleModel, 'page') && $possibleModel != "page")
            $possibleModel = substr($possibleModel, 0, strlen($possibleModel) - 4);

        if (empty($possibleModel))
            throw new CannotGuessModelException('Cannot guess model for page ' . get_class($this) . '.');

        if (!mezzo()->knowsModel($possibleModel))
            throw new CannotGuessModelException('Cannot guess model for page ' . get_class($this) . '. ' .
                'A model with the name ' . $possibleModel . ' is not reflected');

        return $possibleModel;
    }

    /**
     * @return bool
     * @throws ModulePageException
     */
    protected function assertThatPageHasModel()
    {
        if (!$this->model())
            throw new ModulePageException('Cannot find a model for this resource page.');

        return true;
    }


    /**
     * @return string
     */
    public function slug()
    {
        $slug = parent::slug();

        $slugParts = explode('.', $slug);

        if (!in_array(strtolower($slugParts[0]), static::$types)) {
            return $slug;
        }

        $slugParts[] = $slugParts[0];
        unset($slugParts[0]);

        return implode('.', $slugParts);
    }

    /**
     * @param string $type
     * @return ModulePage|null
     * @throws \MezzoLabs\Mezzo\Exceptions\InvalidArgumentException
     */
    public function sibling(string $type = "index")
    {
        $types = static::$types;

        $types = array_map(function ($c_type) {
            return ucfirst($c_type);
        }, $types);

        $name = str_replace($types, ucfirst($type), get_class($this));

        if (!class_exists($name)) {
            return null;
        }

        return $this->module->makePage($name);
    }

    public function isType(string $type)
    {
        return strtolower($this->getType()) == strtolower($type);
    }

    public function getType() : string
    {
        if ($this instanceof CreateResourcePage)
            return "create";

        if ($this instanceof EditResourcePage)
            return "edit";

        if ($this instanceof IndexResourcePage)
            return "index";

        return "";
    }

    /**
     * @param array $merge
     * @return Collection
     */
    public function defaultIncludes($merge = [])
    {
        return $this->model()->defaultIncludes('index', $merge);
    }

    /**
     * @return Collection | mixed
     */
    public function frontendOption($key = null, $value = null)
    {
        if ($key !== null && $value !== null) {
            $this->frontendOptions[$key] = $value;

            return $value;
        }

        if ($key !== null) {
            return $this->frontendOptions[$key];
        }

        return new Collection($this->frontendOptions);
    }


}